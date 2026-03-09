<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectoryRecord extends Model
{
    protected $table = 'directory';
    protected $fillable = [
        'woreda_id',
        'name_en',
        'name_am',
        'name_or',
        'position_en',
        'position_am',
        'position_or',
        'department_en',
        'department_am',
        'department_or',
        'phone',
        'email',
        'office_location',
        'photo_url',
        'category',
        'sort_order',
        'is_active'
    ];

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }
}
