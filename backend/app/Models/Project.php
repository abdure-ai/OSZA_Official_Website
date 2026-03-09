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
        'start_date',
        'end_date',
        'status',
        'budget',
        'progress',
        'contractor',
        'funding_source',
        'is_published',
        'cover_image_url'
    ];
}
