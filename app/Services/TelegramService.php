<?php

namespace App\Services;

use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\User;
use App\Models\TelegramNotification;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Send a notification to a user.
     *
     * @param User $user
     * @param string $message
     * @param string $type
     * @param array $buttons (optional) [['text' => 'Label', 'url' => 'https://...'], ...]
     * @param bool $force (optional) Bypass snooze check
     * @return bool
     */
    public function sendNotification(User $user, string $message, string $type, array $buttons = [], bool $force = false)
    {
        try {
            if (!$user->telegram_chat_id) {
                Log::warning("Telegram: User {$user->id} ({$user->name}) has no telegram_chat_id. Type: {$type}");
                return false;
            }

            if (!$user->telegram_notifications_enabled && !$force) {
                 Log::info("Telegram: User {$user->id} has notifications disabled. Type: {$type}");
                return false;
            }

            // Check Snooze
            if (!$force && $user->telegram_snooze_until && now()->lt($user->telegram_snooze_until)) {
                Log::info("Telegram: User {$user->id} snoozed notifications until {$user->telegram_snooze_until}. Type: {$type}");
                return false;
            }

            $notification = TelegramNotification::create([
                'user_id' => $user->id,
                'type' => $type,
                'message' => $message,
                'status' => 'pending',
                'attempt_count' => 0,
            ]);

            try {
                $params = [
                    'chat_id' => $user->telegram_chat_id,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                ];

                if (!empty($buttons)) {
                    $keyboard = Keyboard::make()->inline();
                    $row = [];
                    foreach ($buttons as $btn) {
                        // Support URL buttons or Callback Data
                        if (isset($btn['url'])) {
                            $row[] = Keyboard::inlineButton(['text' => $btn['text'], 'url' => $btn['url']]);
                        } elseif (isset($btn['callback_data'])) {
                            $row[] = Keyboard::inlineButton(['text' => $btn['text'], 'callback_data' => $btn['callback_data']]);
                        } else {
                            // Fallback or simple text button (not valid in inline usually, but for structure)
                            $row[] = Keyboard::inlineButton(['text' => $btn['text'], 'callback_data' => 'ignore']);
                        }
                    }
                    $keyboard->row($row);
                    $params['reply_markup'] = $keyboard;
                }

                Telegram::sendMessage($params);

                $notification->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'attempt_count' => 1,
                ]);

                return true;

            } catch (\Exception $e) {
                Log::error('Telegram Send Failed: ' . $e->getMessage());
                $notification->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'attempt_count' => 1,
                ]);
                return false;
            }
        } catch (\Throwable $e) {
            Log::error('Telegram Service Fatal Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Templates
     */
    
    public function sendPaymentConfirmation(User $user, $appointment)
    {
        $message = "<b>✅ Payment Successful!</b>\n\n" .
                   "Appointment: " . ($appointment->type ?? 'General Consultation') . "\n" .
                   "Date: " . $appointment->appointment_date . "\n" .
                   "Time: " . $appointment->appointment_time . "\n" .
                   "Amount: RM" . number_format($appointment->payment->amount ?? 0, 2) . "\n" .
                   "Reference: " . ($appointment->payment->receipt_number ?? '-') . "\n\n" .
                   "Your appointment is confirmed!";

        $buttons = [
            ['text' => 'View Details', 'url' => route('patient.appointments.index')], // Assuming route exists
        ];

        return $this->sendNotification($user, $message, 'payment_confirmation', $buttons, true);
    }

    public function sendReminder(User $user, $appointment, $daysBefore)
    {
        $message = "<b>⏰ Reminder: Appointment in {$daysBefore} Days</b>\n\n" .
                   "You have an appointment on {$appointment->appointment_date} at {$appointment->appointment_time}\n" .
                   "Doctor: " . ($appointment->doctor->name ?? 'Any Doctor') . "\n" .
                   "Location: SmartMed Clinic\n\n" .
                   "Please arrive 15 minutes early.";

        $buttons = [
             ['text' => 'Confirm Attendance', 'callback_data' => 'confirm_' . $appointment->id],
             ['text' => 'Reschedule', 'url' => route('patient.appointments.reschedule', $appointment->id)], // Assuming route
        ];

        return $this->sendNotification($user, $message, "reminder_{$daysBefore}day", $buttons);
    }

    public function sendQueueAssigned(User $user, $appointment)
    {
        $message = "<b>📋 Queue Assigned</b>\n\n" .
                   "Your queue number: <b>{$appointment->queue_number}</b>\n" .
                   "Current status: Waiting\n\n" .
                   "You will receive updates as you move up.";

        $buttons = [
            ['text' => 'Check Status', 'callback_data' => 'check_queue_' . $appointment->id],
        ];

        return $this->sendNotification($user, $message, 'queue_assigned', $buttons, true);
    }

    public function sendDoctorCalling(User $user, $appointment)
    {
        $message = "<b>🚨 YOUR TURN NOW</b>\n\n" .
                   "Queue #{$appointment->queue_number}: Please proceed to the consultation room.\n" .
                   "Doctor: Dr. " . ($appointment->doctor->name ?? 'Assigned Doctor') . "\n\n" .
                   "If you miss this call, you may be moved to the end.";

        $buttons = [
            ['text' => "I'm Here", 'callback_data' => 'im_here_' . $appointment->id],
        ];

        return $this->sendNotification($user, $message, 'doctor_calling', $buttons, true);
    }

    public function sendQueuePositionUpdate(User $user, $appointment, $position, $estimatedWait)
    {
        $msg = "";
        if ($position <= 1) {
             $msg = "<b>Please proceed to reception.</b> You are next.";
        } elseif ($position == 2) {
             $msg = "<b>Please prepare, you're next after current patient.</b>";
        } elseif ($position <= 3) {
             $msg = "<b>Approximately " . ($estimatedWait) . " minutes remaining.</b>";
        } elseif ($position <= 5) {
             $msg = "<b>You're in the top 5.</b> Please be ready.";
        } else {
            return false; // Don't spam if position > 5
        }

        $message = "<b>Queue Update</b>\n\n" .
                   "Current Position: {$position}\n" .
                   $msg;

        return $this->sendNotification($user, $message, 'queue_update');
    }

    public function sendFollowUp(User $user, $appointment)
    {
        $message = "<b>✅ Appointment Completed</b>\n\n" .
                   "Thank you for visiting SmartMed Clinic.\n" .
                   "We hope you had a pleasant experience.\n\n" .
                   "Take care!";

        $buttons = [
            ['text' => 'Rate Experience', 'url' => route('patient.appointments.history', ['rate_appointment' => $appointment->id])],
        ];

        return $this->sendNotification($user, $message, 'follow_up', $buttons);
    }

    public function sendSkippedNotification(User $user, $appointment)
    {
        $message = "<b>⚠️ You Missed Your Turn</b>\n\n" .
                   "Queue #{$appointment->queue_number}: You were called but did not arrive.\n" .
                   "Your status has been changed to <b>Skipped</b>.\n\n" .
                   "Please contact the nurse counter immediately to be re-queued.";

        return $this->sendNotification($user, $message, 'queue_skipped', [], true);
    }

    public function sendDelayNotification(User $user, $appointment, $minutes)
    {
        $message = "<b>⚠️ Appointment Delayed</b>\n\n" .
                   "Your appointment is delayed by approximately {$minutes} minutes.\n" .
                   "We apologize for the inconvenience.";

        return $this->sendNotification($user, $message, 'delay_notification');
    }
}
