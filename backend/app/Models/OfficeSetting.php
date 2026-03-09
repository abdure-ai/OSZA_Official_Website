<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSetting extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'address',
        'address_am',
        'address_or',
        'working_hours',
        'working_hours_am',
        'working_hours_or',
        'map_url',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url'
    ];
}
