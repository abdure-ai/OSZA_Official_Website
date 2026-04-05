<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{
    protected $table = 'leadership';
    protected $fillable = [
        'name_en',
        'name_am',
        'name_or',
        'position_en',
        'position_am',
        'position_or',
        'bio_en',
        'bio_am',
        'bio_or',
        'photo_url',
        'rank_order',
        'parent_id',
        'hierarchy_level',
        'email',
        'phone',
        'office_location_en',
        'office_location_am',
        'office_location_or'
    ];

    public function parent()
    {
        return $this->belongsTo(Leadership::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Leadership::class, 'parent_id')->orderBy('rank_order');
    }
}
