<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition',
        'description',
        'diagnosed_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
