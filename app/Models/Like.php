<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'id_key','name','id_user'
    ];

    protected $table = 'likes';
}
