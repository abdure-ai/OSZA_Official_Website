<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyAlert extends Model
{
    protected $table = 'emergency_alerts';
    protected $fillable = [
        'message_en',
        'message_am',
        'message_or',
        'severity',
        'is_active',
        'expires_at'
    ];
}
