<?php
use App\Models\Appointment;
use Carbon\Carbon;

$today = Carbon::today()->format('Y-m-d');
echo "Checking for date: " . $today . "\n";

$appointments = Appointment::where('appointment_date', $today)->get();
echo "Total appointments today: " . $appointments->count() . "\n";

foreach ($appointments as $apt) {
    echo "ID: " . $apt->id . " | Status: " . $apt->status . " | Queue: " . ($apt->queue_number ?? 'NULL') . " | QStatus: " . ($apt->queue_status ?? 'NULL') . "\n";
}
