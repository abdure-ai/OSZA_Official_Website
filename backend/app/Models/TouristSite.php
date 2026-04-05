<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TouristSite extends Model
{
    /** @use HasFactory<\Database\Factories\TouristSiteFactory> */
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_am',
        'name_or',
        'slug',
        'description_en',
        'description_am',
        'description_or',
        'category',
        'woreda_id',
        'location_name_en',
        'cover_image_url',
        'video_url',
        'gallery_urls',
        'latitude',
        'longitude',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'gallery_urls' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
}
