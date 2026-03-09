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
        'rank_order'
    ];
}
