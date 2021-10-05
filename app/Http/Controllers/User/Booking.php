<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Categorie;
use App\Models\Like;
use App\Models\Reserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Booking extends Controller
{
    use GeneralTrait;
    public function reserve($id){
        $user=Auth::user();
        $catg=Categorie::find($id);
        if($catg){
        Reserve::create([

            'user_id'=>$user->id,
            'name_user'=>$user->name,
            'status' =>false,
            'categories_id'=>$id,
            ]);
        return $this->returnSuccessMessage("s000",'Booking Has Been sent');}
        else{
            return $this->returnError('E43','This Item is Not Exist');
        }

    }

    public function like($id){
        $user=Auth()->user();
        $catg=Categorie::find($id);
        if($catg){
          $like = Like::select()->where('id_user',$user->id)->where('id_key',$id)->get();

        if(count($like)==0){
        Like::create([
            'id_user'=>$user->id,
            'name'=>$user->name,
            'id_key'=>$id
        ]);
        return $this->returnSuccessMessage('s000',"Like Has been setting");}
        else{
            Like::where('id_user',$user->id)->where('id_key',$id)->delete();
            return $this->returnSuccessMessage('s000',"Like Has been removed");

        }}
        else{
            return $this->returnError('E43','This Item is Not Exist');

        }
    }
}
