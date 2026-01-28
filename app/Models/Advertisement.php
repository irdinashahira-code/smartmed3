<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'content',
        'image_path',
        'cta_text',
        'cta_link',
        'target_age_min',
        'target_age_max',
        'target_gender',
        'target_conditions',
        'start_date',
        'end_date',
        'priority',
        'is_active'
    ];

    protected $casts = [
        'target_conditions' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function interactions()
    {
        return $this->hasMany(AdInteraction::class);
    }
}
