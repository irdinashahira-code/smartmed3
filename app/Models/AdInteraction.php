<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdInteraction extends Model
{
    use HasFactory;

    public $timestamps = false; // We use created_at via database default or manual set

    protected $fillable = [
        'advertisement_id',
        'user_id',
        'interaction_type',
        'created_at'
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
