<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use App\Services\TelegramService;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private $stripeSecret;
    private $stripeKey;
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->stripeSecret = env('STRIPE_SECRET');
        $this->stripeKey = env('STRIPE_KEY');
        $this->telegramService = $telegramService;
    }

    public function initiate(Appointment $appointment)
    {
        Log::info('Initiating payment for appointment: ' . $appointment->id);
        
        if ($appointment->user_id !== Auth::id()) {
            Log::warning('User mismatch for appointment: ' . $appointment->id);
            abort(403);
        }

        // Stripe expects amount in cents (RM 2.00 = 200 cents) - Minimum amount for MYR is RM 2.00
        $amountCents = 200;
        
        Log::info('Stripe Secret Key length: ' . strlen($this->stripeSecret));

        try {
            $response = Http::withToken($this->stripeSecret)
                ->asForm()
                ->post('https://api.stripe.com/v1/checkout/sessions', [
                    'payment_method_types' => ['card', 'fpx'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'myr',
                            'product_data' => [
                                'name' => 'SmartMed Appointment',
                                'description' => 'Appointment ID: ' . $appointment->id,
                            ],
                            'unit_amount' => $amountCents,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => config('app.url') . '/payment/response?session_id={CHECKOUT_SESSION_ID}&order_id=' . $appointment->id,
                    'cancel_url' => config('app.url') . '/patient/appointments',
                    'client_reference_id' => $appointment->id,
                    'customer_email' => $appointment->patient->email,
                ]);

            $session = $response->json();

            if (isset($session['url'])) {
                Log::info('Stripe Session Created: ' . $session['url']);
                return redirect($session['url']);
            }

            Log::error('Stripe Session Creation Failed', ['response' => $session]);
            return back()->with('error', 'Unable to initiate payment: ' . ($session['error']['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Stripe Exception: ' . $e->getMessage());
            return back()->with('error', 'Payment system error: ' . $e->getMessage());
        }
    }

    public function response(Request $request)
    {
        $sessionId = $request->input('session_id');
        $orderId = $request->input('order_id');

        if (!$sessionId) {
            return redirect()->route('patient.appointments.index')->with('error', 'Invalid payment session.');
        }

        // Verify the session with Stripe
        $response = Http::withToken($this->stripeSecret)
            ->get('https://api.stripe.com/v1/checkout/sessions/' . $sessionId);
            
        $session = $response->json();

        if (isset($session['payment_status']) && $session['payment_status'] === 'paid') {
            
            $appointment = Appointment::find($orderId);
            
            if ($appointment && $appointment->status !== 'paid') {
                $appointment->update(['status' => 'paid']);
                Payment::create([
                    'appointment_id' => $appointment->id,
                    'receipt_number' => $session['payment_intent'] ?? $sessionId,
                    'amount' => $session['amount_total'] / 100,
                    'payment_date' => now(),
                    'status' => 'paid',
                ]);
                
                // Send Telegram Notification
                try {
                    $sent = $this->telegramService->sendPaymentConfirmation($appointment->patient, $appointment);
                    
                    if (!$sent) {
                        session()->flash('warning', 'Payment successful, but we could not send the Telegram confirmation. Please ensure your Telegram account is linked in your profile.');
                    }

                    // Auto-Assign Queue if appointment is TODAY
                    if ($appointment->appointment_date == Carbon::today()->format('Y-m-d')) {
                        $maxQueue = Appointment::whereDate('appointment_date', Carbon::today()->format('Y-m-d'))
                            ->max('queue_number') ?? 0;

                        if (!$appointment->queue_number) {
                            $appointment->update([
                                'queue_number' => $maxQueue + 1,
                                'queue_status' => 'waiting',
                                'checked_in_at' => now(),
                            ]);
                            
                            $this->telegramService->sendQueueAssigned($appointment->patient, $appointment);
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Telegram Payment Notification Failed: " . $e->getMessage());
                }
            }
            
            return redirect()->route('patient.appointments.receipt', $appointment->id)->with('success', 'Payment successful! Your appointment is confirmed.');
        }

        return redirect()->route('patient.appointments.index')->with('error', 'Payment failed or cancelled.');
    }

    public function backend(Request $request)
    {
        // Stripe Webhook handler would go here
        // For "ASAP" integration, we rely on the secure redirect verification above.
        // To implement this properly, we'd need to verify the Stripe signature header.
        return response('OK');
    }
}
