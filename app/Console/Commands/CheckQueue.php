<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CheckQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Queue Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        $this->info("Checking for date: " . $today);

        $appointments = Appointment::where('appointment_date', $today)->get();
        $this->info("Total appointments today: " . $appointments->count());

        foreach ($appointments as $apt) {
            $this->line("ID: {$apt->id} | Patient: {$apt->user_id} | Doctor: {$apt->doctor_id} | Status: {$apt->status} | Queue: " . ($apt->queue_number ?? 'NULL') . " | QStatus: " . ($apt->queue_status ?? 'NULL'));
        }
    }
}
