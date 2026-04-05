<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = [
        'type',
        'title_en',
        'title_am',
        'title_or',
        'content_en',
        'content_am',
        'content_or',
        'image_url',
        'icon',
        'sort_order',
        'is_active'
    ];
}
