<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'file_url',
        'file_type',
        'category',
        'cover_image_url',
        'author',
        'description_en',
        'pages',
        'language',
        'uploaded_by'
    ];
}
