<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = [
        'name', 'id_item', 'location','price','price_discount','description','photo_center','status','view_in_home',
        'discount'
    ];




    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
public function items(){
    return $this->belongsTo(item::class, 'id_item');

}

}
