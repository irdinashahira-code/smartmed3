<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\User;
use App\Models\Appointment;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function webhook()
    {
        Log::info('Webhook hit!'); 
        try {
            $update = Telegram::getWebhookUpdate();
            
            // Log raw update for debugging
            Log::info('Webhook Update:', (array)$update);

            // Handle Callback Queries (Buttons)
            if ($update->isType('callback_query')) {
                $this->handleCallbackQuery($update->getCallbackQuery());
                return response()->json(['status' => 'ok']);
            }

            $message = $update->getMessage();
            
            if (!$message) {
                Log::info('Webhook: No message found in update');
                return response()->json(['status' => 'ok']);
            }

            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            Log::info("Telegram Webhook Text: " . $text);

            if (strpos($text, '/start') === 0) {
                Log::info("Handling /start command");
                $parts = explode(' ', $text);
                $payload = $parts[1] ?? null;
                $this->handleStart($chatId, $payload);
            } elseif (strpos($text, '/link') === 0) {
                $parts = explode(' ', $text);
                $patientId = $parts[1] ?? null;
                $this->handleLink($chatId, $patientId);
            } elseif (strpos($text, '/appointments') === 0) {
                $this->handleAppointments($chatId);
            } elseif (strpos($text, '/checkin') === 0) {
                $this->handleCheckin($chatId);
            } elseif (strpos($text, '/queuestatus') === 0) {
                $this->handleQueueStatus($chatId);
            } elseif (strpos($text, '/snooze') === 0) {
                // /snooze 30
                $parts = explode(' ', $text);
                $minutes = $parts[1] ?? 60; // Default 60 mins
                $this->handleSnooze($chatId, $minutes);
            } elseif (strpos($text, '/notifications') === 0) {
                // /notifications off
                $parts = explode(' ', $text);
                $action = $parts[1] ?? 'status';
                $this->handleNotificationSettings($chatId, $action);
            } elseif (strpos($text, '/help') === 0) {
                $this->handleHelp($chatId);
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Telegram Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    protected function handleStart($chatId, $payload = null)
    {
        // Check for Deep Linking Payload
        if ($payload && strpos($payload, 'link_') === 0) {
            // Format: link_USERID_SIGNATURE
            $parts = explode('_', $payload);
            
            if (count($parts) >= 3) {
                $userId = $parts[1];
                $signature = $parts[2];
                
                // Validate Signature (Simple MD5 of ID + APP_KEY)
                $expected = substr(md5($userId . config('app.key')), 0, 10);
                
                if ($signature === $expected) {
                    $user = User::find($userId);
                    
                    if ($user) {
                        // Link User
                        $user->update([
                            'telegram_chat_id' => $chatId,
                            'telegram_notifications_enabled' => true
                        ]);
                        
                        $msg = "✅ *Account Linked Successfully!*\n\n" .
                               "Hello, {$user->name}.\n" .
                               "You will now receive automatic updates for your appointments here.";
                        
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => $msg,
                            'parse_mode' => 'Markdown'
                        ]);
                        
                        // Check for today's active appointment and send status immediately
                        $this->sendCurrentStatus($user, $chatId);
                        
                        return;
                    }
                }
            }
            
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "⚠️ Invalid or expired linking code."
            ]);
            return;
        }

        $msg = "Welcome to SmartMed Bot! 🏥\n\n" .
               "Please link your account using:\n" .
               "/link <patient_id>\n\n" .
               "Example: /link 5";
        
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $msg
        ]);
    }

    protected function sendCurrentStatus($user, $chatId)
    {
        $today = Carbon::today()->format('Y-m-d');
        $appointment = Appointment::where('user_id', $user->id)
            ->where('appointment_date', $today)
            ->where('status', 'paid')
            ->first();

        if ($appointment) {
             $msg = "📋 *Current Appointment Status*\n\n" .
                    "Queue: *" . ($appointment->queue_number ?? 'Not Assigned') . "*\n" .
                    "Status: " . ucfirst($appointment->queue_status ?? 'Waiting');
             
             Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $msg,
                'parse_mode' => 'Markdown'
            ]);
        }
    }

    protected function handleLink($chatId, $patientId)
    {
        if (!$patientId) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Please provide your Patient ID.\nUsage: /link <patient_id>"
            ]);
            return;
        }

        $user = User::find($patientId);

        if (!$user) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Patient ID not found."
            ]);
            return;
        }

        // Security check: Check if already linked to another chat
        if ($user->telegram_chat_id && $user->telegram_chat_id != $chatId) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "This account is already linked to another Telegram user."
            ]);
            return;
        }

        $user->update(['telegram_chat_id' => $chatId]);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "✅ Account linked successfully!\nHello, {$user->name}.\n\nYou will now receive notifications here."
        ]);
    }

    protected function handleCheckin($chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "Please /link your account first."]);
            return;
        }

        $today = Carbon::today()->format('Y-m-d');
        
        // Find paid appointment for today
        $appointment = Appointment::where('user_id', $user->id)
            ->where('appointment_date', $today)
            ->where('status', 'paid')
            ->first();

        if (!$appointment) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "No paid appointment found for today to check in."]);
            return;
        }

        if ($appointment->queue_number) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "You are already checked in.\nQueue Number: {$appointment->queue_number}"]);
            return;
        }

        // Assign Queue Logic (Duplicated from QueueController for simplicity in this context)
        $maxQueue = Appointment::whereDate('appointment_date', $today)->max('queue_number') ?? 0;
        
        $appointment->update([
            'queue_number' => $maxQueue + 1,
            'queue_status' => 'waiting',
            'checked_in_at' => now(),
        ]);

        try {
            $this->telegramService->sendQueueAssigned($user, $appointment);
        } catch (\Throwable $e) {
            Log::error("Telegram Webhook Queue Notification Failed: " . $e->getMessage());
        }
    }

    protected function handleQueueStatus($chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "Please /link your account first."]);
            return;
        }

        $today = Carbon::today()->format('Y-m-d');
        $appointment = Appointment::where('user_id', $user->id)
            ->where('appointment_date', $today)
            ->whereNotNull('queue_number')
            ->whereIn('queue_status', ['waiting', 'called'])
            ->first();

        if (!$appointment) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "You are not currently in a queue."]);
            return;
        }

        // Calculate position
        $peopleAhead = Appointment::where('appointment_date', $today)
            ->where('queue_status', 'waiting')
            ->where('queue_number', '<', $appointment->queue_number)
            ->count();
        
        $position = $peopleAhead + 1;
        $estimatedWait = $position * 15;

        $msg = "<b>Current Queue Status</b>\n\n" .
               "Queue Number: <b>{$appointment->queue_number}</b>\n" .
               "Position in Line: {$position}\n" .
               "Est. Wait Time: {$estimatedWait} mins\n" .
               "Status: " . ucfirst($appointment->queue_status);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $msg,
            'parse_mode' => 'HTML'
        ]);
    }

    protected function handleAppointments($chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "Please /link your account first."]);
            return;
        }

        $appointments = Appointment::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->where('appointment_date', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy('appointment_date', 'asc')
            ->take(5)
            ->get();

        if ($appointments->isEmpty()) {
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "You have no upcoming appointments."]);
            return;
        }

        $msg = "<b>📅 Upcoming Appointments</b>\n\n";
        foreach ($appointments as $apt) {
            $msg .= "Date: {$apt->appointment_date} at {$apt->appointment_time}\n";
            $msg .= "Doctor: " . ($apt->doctor->name ?? 'Any') . "\n";
            $msg .= "Status: " . ucfirst($apt->status) . "\n";
            $msg .= "-------------------\n";
        }

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $msg,
            'parse_mode' => 'HTML'
        ]);
    }

    protected function handleSnooze($chatId, $minutes)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
             Telegram::sendMessage(['chat_id' => $chatId, 'text' => "Please /link your account first."]);
             return;
        }

        $snoozeUntil = Carbon::now()->addMinutes((int)$minutes);
        $user->update(['telegram_snooze_until' => $snoozeUntil]);
        
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "😴 Notifications snoozed until " . $snoozeUntil->format('H:i') . "."
        ]);
    }

    protected function handleNotificationSettings($chatId, $action)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) return;

        if ($action === 'on') {
            $user->update(['telegram_notifications_enabled' => true]);
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "🔔 Notifications enabled."]);
        } elseif ($action === 'off') {
            $user->update(['telegram_notifications_enabled' => false]);
            Telegram::sendMessage(['chat_id' => $chatId, 'text' => "🔕 Notifications disabled."]);
        } else {
             $status = $user->telegram_notifications_enabled ? "ON" : "OFF";
             Telegram::sendMessage(['chat_id' => $chatId, 'text' => "Current Notification Status: {$status}\nUse /notifications on or /notifications off"]);
        }
    }

    protected function handleHelp($chatId)
    {
        $msg = "<b>Available Commands:</b>\n\n" .
               "/start - Connect SmartMed account\n" .
               "/link [id] - Link Telegram to your account\n" .
               "/appointments - View upcoming appointments\n" .
               "/checkin - Check in for today's appointment\n" .
               "/queuestatus - Get current queue position\n" .
               "/snooze [mins] - Pause notifications\n" .
               "/notifications [on/off] - Toggle settings\n" .
               "/help - Show this message";

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $msg,
            'parse_mode' => 'HTML'
        ]);
    }

    protected function handleCallbackQuery($callbackQuery)
    {
        $data = $callbackQuery->getData();
        $chatId = $callbackQuery->getFrom()->getId();
        
        Log::info("Handling callback query: " . $data);

        if (strpos($data, 'check_queue_') === 0) {
            $appointmentId = str_replace('check_queue_', '', $data);
            $this->handleQueueStatusCheck($chatId, $appointmentId);
        }

        // Answer callback to stop loading state
        Telegram::answerCallbackQuery([
            'callback_query_id' => $callbackQuery->getId()
        ]);
    }

    protected function handleQueueStatusCheck($chatId, $appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Appointment not found."
            ]);
            return;
        }

        // Calculate position if waiting
        $msg = "";
        if ($appointment->queue_status == 'waiting') {
            $today = \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d');
            
            // Count people ahead based on TIME (not queue number)
            $peopleAhead = Appointment::where('appointment_date', $today)
                ->where('doctor_id', $appointment->doctor_id)
                ->where('queue_status', 'waiting')
                ->where('appointment_time', '<', $appointment->appointment_time)
                ->count();
            
            // Check if someone is currently consulting
            $currentServing = Appointment::where('appointment_date', $today)
                ->where('doctor_id', $appointment->doctor_id)
                ->whereIn('queue_status', ['called', 'consulting'])
                ->exists();
            
            $position = $peopleAhead + 1;
            
            // Calculate Wait Time
            // 1. Queue Processing Time: (People Ahead * 15m) + (Current Serving * 15m)
            $queueWait = ($peopleAhead * 15) + ($currentServing ? 15 : 0);
            
            // 2. Schedule Wait Time: Time until appointment
            $apptTime = \Carbon\Carbon::parse($today . ' ' . $appointment->appointment_time);
            $now = \Carbon\Carbon::now('Asia/Kuala_Lumpur');
            $scheduleWait = $now->diffInMinutes($apptTime, false); // false = return negative if past
            if ($scheduleWait < 0) $scheduleWait = 0;
            
            // Final Estimated Wait is MAX of both
            $estimatedWait = max($queueWait, $scheduleWait);
            
            $msg = "<b>Queue Status Update</b>\n\n" .
                   "Queue Number: <b>{$appointment->queue_number}</b>\n" .
                   "Position: {$position}\n" .
                   "Est. Wait: {$estimatedWait} mins\n" .
                   "Status: Waiting";
        } else {
            $msg = "<b>Queue Status Update</b>\n\n" .
                   "Queue Number: <b>{$appointment->queue_number}</b>\n" .
                   "Status: " . ucfirst($appointment->queue_status);
        }

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $msg,
            'parse_mode' => 'HTML'
        ]);
    }

    protected function handleImHere($chatId, $appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
             Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Appointment not found."
            ]);
            return;
        }

        // Verify user
        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user || $appointment->user_id !== $user->id) {
             Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Unauthorized action."
            ]);
            return;
        }

        // Only allow if status is 'called'
        if ($appointment->queue_status !== 'called') {
             Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "You can only mark yourself as 'Here' when you have been called."
            ]);
            return;
        }

        $appointment->update(['queue_status' => 'arrived']);

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "✅ *Confirmed!* \n\nThe doctor has been notified that you are here. Please wait for the nurse to invite you in.",
            'parse_mode' => 'Markdown'
        ]);
        
        Log::info("Patient marked as arrived via Telegram. Appt ID: {$appointment->id}");
    }
}
