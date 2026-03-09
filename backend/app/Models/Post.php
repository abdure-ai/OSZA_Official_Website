<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'content_en',
        'content_am',
        'content_or',
        'category',
        'status',
        'thumbnail_url',
        'admin_id',
        'woreda_id',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * The woreda this post belongs to (if any).
     */
    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
}
