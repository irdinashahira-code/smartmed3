<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramNotification;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class RetryTelegramNotifications extends Command
{
    protected $signature = 'notifications:retry-telegram';
    protected $description = 'Retry failed Telegram notifications and fallback to email';

    public function handle()
    {
        $this->info('Checking for failed notifications...');

        $failedNotifications = TelegramNotification::where('status', 'failed')
            ->where('attempt_count', '<', 4) // Retry up to 3 times (Total 4 attempts including first)
            ->get();

        foreach ($failedNotifications as $notification) {
            $now = Carbon::now();
            $lastAttempt = Carbon::parse($notification->updated_at);
            $attempts = $notification->attempt_count;

            $shouldRetry = false;

            // Strategy:
            // 1st attempt (immediate) - failed.
            // 2nd attempt: +5 mins
            // 3rd attempt: +15 mins (from last attempt)
            // 4th attempt: Fallback

            if ($attempts == 1 && $lastAttempt->diffInMinutes($now) >= 5) {
                $shouldRetry = true;
            } elseif ($attempts == 2 && $lastAttempt->diffInMinutes($now) >= 15) {
                $shouldRetry = true;
            } elseif ($attempts >= 3) {
                // Mark as final failed and fallback
                $this->fallbackToEmail($notification);
                continue;
            }

            if ($shouldRetry) {
                $this->retryNotification($notification);
            }
        }
    }

    protected function retryNotification($notification)
    {
        $this->info("Retrying notification ID: {$notification->id} (Attempt {$notification->attempt_count})");

        try {
            // We use Telegram facade directly here since we have the raw data
            Telegram::sendMessage([
                'chat_id' => $notification->user->telegram_chat_id,
                'text' => $notification->message,
                'parse_mode' => 'HTML',
            ]);

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
                'attempt_count' => $notification->attempt_count + 1,
            ]);

            $this->info("Success.");

        } catch (\Exception $e) {
            Log::error("Retry Failed for ID {$notification->id}: " . $e->getMessage());
            $notification->update([
                'status' => 'failed',
                'attempt_count' => $notification->attempt_count + 1,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    protected function fallbackToEmail($notification)
    {
        $this->info("Fallback to Email for ID: {$notification->id}");

        $user = $notification->user;
        if (!$user || !$user->email) {
            $notification->update(['status' => 'final_failed_no_email']);
            return;
        }

        // Send Email (Simplistic implementation)
        try {
            Mail::raw($notification->message, function ($message) use ($user, $notification) {
                $message->to($user->email)
                    ->subject('SmartMed Notification: ' . ucfirst(str_replace('_', ' ', $notification->type)));
            });

            $notification->update([
                'status' => 'fallback_email_sent',
                'error_message' => 'Telegram failed 3 times. Sent via Email.',
            ]);

        } catch (\Exception $e) {
            Log::error("Email Fallback Failed for ID {$notification->id}: " . $e->getMessage());
            $notification->update([
                'status' => 'final_failed_email_error',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
