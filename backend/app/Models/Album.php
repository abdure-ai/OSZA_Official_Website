<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'description_en',
        'description_am',
        'description_or',
        'cover_image_url',
        'category',
        'woreda_id',
        'sort_order',
        'is_active'
    ];

    public function items()
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
}
