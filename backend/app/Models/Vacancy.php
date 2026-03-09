<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'title_en',
        'title_am',
        'title_or',
        'description_en',
        'description_am',
        'description_or',
        'requirements_en',
        'requirements_am',
        'requirements_or',
        'department',
        'vacancy_type',
        'location_en',
        'deadline',
        'is_active'
    ];
}
