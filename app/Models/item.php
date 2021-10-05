<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    protected $fillable = [
        'name',
    ];


    public function Ctegories(){
        return $this->hasMany(Categorie::class, 'id_item');
    }
}
