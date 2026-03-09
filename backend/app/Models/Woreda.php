<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Woreda extends Model
{
    protected $fillable = [
        'name_en',
        'name_am',
        'name_or',
        'slug',
        'description_en',
        'description_am',
        'description_or',
        'population',
        'area_km2',
        'established_year',
        'capital_en',
        'capital_am',
        'capital_or',
        'administrator_name',
        'administrator_title',
        'administrator_photo_url',
        'contact_phone',
        'contact_email',
        'address_en',
        'address_am',
        'address_or',
        'mission_en',
        'mission_am',
        'mission_or',
        'vision_en',
        'vision_am',
        'vision_or',
        'banner_url',
        'logo_url',
        'is_active'
    ];

    /**
     * Gallery items for this woreda.
     */
    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class);
    }

    /**
     * Posts/news for this woreda.
     */
    /**
     * Directory records (staff list) for this woreda.
     */
    /**
     * Service sectors mapping for this woreda.
     */
    public function serviceSectors()
    {
        return $this->belongsToMany(ServiceSector::class, 'woreda_service_sectors')
            ->withPivot([
                'official_name_en',
                'official_name_am',
                'official_name_or',
                'official_title_en',
                'official_title_am',
                'official_title_or',
                'official_phone',
                'official_email',
                'official_photo_url',
                'is_active'
            ])
            ->withTimestamps();
    }
}
