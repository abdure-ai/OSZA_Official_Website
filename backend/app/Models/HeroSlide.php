<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $table = 'hero_slides';
    protected $fillable = [
        'page',
        'title_en',
        'subtitle_en',
        'title_am',
        'subtitle_am',
        'title_or',
        'subtitle_or',
        'media_url',
        'media_type',
        'cta_text',
        'cta_text_am',
        'cta_text_or',
        'cta_url',
        'sort_order',
        'is_active'
    ];
}
