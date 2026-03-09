<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    protected $table = 'admin_messages';
    protected $fillable = [
        'name',
        'title_position',
        'message_en',
        'message_am',
        'message_or',
        'photo_url',
        'is_active'
    ];
}
