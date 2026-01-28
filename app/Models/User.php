<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'google_id',
        'ic_number',
        'age',
        'date_of_birth',
        'gender',
        'phone_number',
        'address',
        'city',
        'state',
        'postcode',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'specialization',
        'qualification',
        'bio',
        'telegram_chat_id',
        'telegram_notifications_enabled',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'telegram_snooze_until' => 'datetime',
    ];

    public function telegramNotifications()
    {
        return $this->hasMany(TelegramNotification::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(DoctorLeave::class, 'user_id');
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class, 'user_id');
    }

    public function consultationNotes()
    {
        return $this->hasMany(ConsultationNote::class, 'user_id'); // As patient
    }

    public function doctorConsultationNotes()
    {
        return $this->hasMany(ConsultationNote::class, 'doctor_id'); // As doctor
    }

    public function feedbacks()
    {
        return $this->hasManyThrough(Feedback::class, Appointment::class, 'doctor_id', 'appointment_id');
    }
}
