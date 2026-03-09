<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'description_en',
        'description_am',
        'description_or',
        'ref_number',
        'deadline',
        'file_url',
        'status'
    ];
}
