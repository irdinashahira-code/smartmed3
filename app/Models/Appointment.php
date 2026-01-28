<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'patient_ic',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'type',
        'reason',
        'weight',
        'status',
        'cancellation_status',
        'reschedule_status',
        'reschedule_data',
        'queue_number',
        'queue_status',
        'checked_in_at',
    ];

    protected $casts = [
        'reschedule_data' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function consultationNote()
    {
        return $this->hasOne(ConsultationNote::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    public function medicalImages()
    {
        return $this->hasMany(MedicalImage::class);
    }
}
