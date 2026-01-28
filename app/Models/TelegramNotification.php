<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'status',
        'attempt_count',
        'sent_at',
        'error_message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
