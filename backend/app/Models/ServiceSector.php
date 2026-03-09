<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSector extends Model
{
    protected $fillable = [
        'name_en',
        'name_am',
        'name_or',
        'description_en',
        'description_am',
        'description_or',
        'icon_svg',
        'is_active',
        'sort_order',
    ];

    /**
     * Woredas using this service sector.
     */
    public function woredas()
    {
        return $this->belongsToMany(Woreda::class, 'woreda_service_sectors')
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
