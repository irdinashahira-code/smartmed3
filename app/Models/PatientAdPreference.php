<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opt_out_types',
    ];

    protected $casts = [
        'opt_out_types' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
