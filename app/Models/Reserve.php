<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    protected $fillable = [
       'user_id','name_user', 'status', 'categories_id',
    ];
}
