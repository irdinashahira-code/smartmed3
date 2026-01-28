<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\TelegramNotification;
use App\Services\TelegramService;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 72-hour and 24-hour appointment reminders via Telegram';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService)
    {
        $this->info('Starting appointment reminders check...');

        $now = Carbon::now();
        
        // 1. Check for 3-Day Reminders (72 hours)
        // We look for appointments roughly 3 days from now. 
        // To handle "every 5 mins" execution, we check a window or rely on "already sent" check.
        // Let's use Date logic: Appointments on (Today + 3 days).
        $targetDate3Day = $now->copy()->addDays(3)->format('Y-m-d');
        $this->processReminders($telegramService, $targetDate3Day, 3);

        // 2. Check for 1-Day Reminders (24 hours)
        $targetDate1Day = $now->copy()->addDay()->format('Y-m-d');
        $this->processReminders($telegramService, $targetDate1Day, 1);

        $this->info('Reminders check completed.');
    }

    protected function processReminders(TelegramService $telegramService, $date, $daysBefore)
    {
        $type = "reminder_{$daysBefore}day";

        // Find appointments for this date that are 'booked' or 'paid'
        $appointments = Appointment::where('appointment_date', $date)
            ->whereIn('status', ['booked', 'paid'])
            ->with(['patient', 'doctor'])
            ->get();

        foreach ($appointments as $appointment) {
            $user = $appointment->patient;
            if (!$user || !$user->telegram_chat_id || !$user->telegram_notifications_enabled) {
                continue;
            }

            // Check if reminder already sent for this specific appointment (approx logic)
            // We check if a notification of this type was sent to this user in the last 24 hours.
            // Note: This assumes 1 appointment per day per user generally, or at least 1 reminder type per day.
            // Ideally, we'd link notification to appointment_id, but for now we check if we sent *this type* recently.
            // To be more precise without appointment_id in notifications table:
            // We can check if the "message" contains the date? Or just rely on daily limit.
            
            $alreadySent = TelegramNotification::where('user_id', $user->id)
                ->where('type', $type)
                ->whereDate('created_at', Carbon::today()) // Sent today?
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $this->info("Sending {$daysBefore}-day reminder to user {$user->id} for appointment {$appointment->id}");
            
            $telegramService->sendReminder($user, $appointment, $daysBefore);
        }
    }
}
