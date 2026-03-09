<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'title_am',
        'title_or',
        'image_url',
        'category',
        'woreda_id',
        'sort_order',
        'is_active'
    ];

    /**
     * The woreda this gallery item belongs to.
     */
    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
}
