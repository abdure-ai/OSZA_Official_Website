<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'description_en',
        'description_am',
        'description_or',
        'location_en',
        'location_am',
        'location_or',
        'start_date',
        'end_date',
        'status',
        'budget',
        'progress',
        'contractor',
        'contractor_am',
        'contractor_or',
        'funding_source',
        'funding_source_am',
        'funding_source_or',
        'is_published',
        'cover_image_url'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
    ];
}
