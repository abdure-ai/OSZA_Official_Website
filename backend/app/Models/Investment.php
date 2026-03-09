<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'description_en',
        'description_am',
        'description_or',
        'category',
        'location',
        'location_am',
        'location_or',
        'budget',
        'incentives_en',
        'incentives_am',
        'incentives_or',
        'contact_name',
        'contact_phone',
        'contact_email',
        'thumbnail_url',
        'status'
    ];
}
