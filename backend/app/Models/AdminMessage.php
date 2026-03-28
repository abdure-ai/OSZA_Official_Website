<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    protected $table = 'admin_messages';
    protected $fillable = [
        'name',
        'name_am',
        'name_or',
        'title_position',
        'title_position_am',
        'title_position_or',
        'message_en',
        'message_am',
        'message_or',
        'photo_url',
        'is_active'
    ];
}
