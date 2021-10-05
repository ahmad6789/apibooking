<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestItem;
use App\Http\Traits\GeneralTrait;
use App\Models\Categorie;
use App\Models\item;
use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;
use Tymon\JWTAuth\JWTAuth;

class Categories extends Controller
{
    use GeneralTrait;

    public function store(RequestItem $request)
    {
        try {


            $filePath = "";
            if ($request->has('photo')) {
                $filePath = $this->uploadImage('CategoriesPhoto', $request->photo);
            }

            Categorie::create([
                'name' => $request->name,
                'price' => $request->price,
                'id_item' => $request->id_item,
                'location' => $request->location,
                'description' => $request->description,
                'status' => $request->status,
                'price_discount' => $request->price_discount,
                'photo_center' => $filePath,
                'discount'=>$request->discount,
                'view_in_home'=>$request->view_in_home
            ]);
            return $this->returnSuccessMessage("s000", "Data Has Been Saved Success");
        } catch (\Exception $e) {
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }
    public function update(RequestItem $request,$id){
        $filePath = "";
        if ($request->has('photo')) {
            $filePath = $this->uploadImage('CategoriesPhoto', $request->photo);
        }
        Categorie::where('id',$id)->update([
            'name' => $request->name,
            'price' => $request->price,
            'id_item' => $request->id_item,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status,
            'price_discount' => $request->price_discount,
            'discount'=>$request->discount,
            'view_in_home'=>$request->view_in_home,
            'photo_center' => $filePath
        ]);
        return $this->returnSuccessMessage("s000", "Data Has Been Updated Success");

    }
    public function EditCategories($id){
        $categories =Categorie::find($id);
        if($categories==null)
            return $this->returnError('E43','This Item is Not Exist');
        else{
            return $this->returnDatas('Categories',$categories);

        }
        }



    public function read(){
        $main=item::with('Ctegories')->select()->get();
        $main=collect($main);
        return response()->json($main);

    }
    public function delete($id){
        try{
            $categories =Categorie::find($id);
if($categories){
            Categorie::where('id',$id)->delete();
        return $this->returnSuccessMessage("s000", "Data Has Been Deleted Success");}
else{
    return $this->returnError('E43','This Item is Not exist');
}
        }catch (\Exception $e){
         return   $this->returnError($e->getCode(),$e->getMessage());
        }
    }
    public function deleteuser($id){
        $user=User::find($id);
        if ($user){
        User::where('id',$id)->delete();
        return $this->returnSuccessMessage("s000", "User Has Been Deleted Success");}
        else {
            $this->returnError('E43','This user is not exist');
        }

    }
    public function showuser(){
      $user=  User::select()->get();
        return response()->json($user);

    }

   public function uploadImage($folder, $image)
    {
        $image->store('/', $folder);
        $filename = $image->hashName();
        $path = 'http://localhost:8000/images/' . $folder . '/' . $filename;
        return $path;
    }


    public function readhome(){
        $main=Categorie::select()->where('view_in_home',1)->get();
        $main=collect($main);
        return response()->json($main);
}
    public function discount(){
        $main=Categorie::select()->where('discount',1)->get();
        $main=collect($main);
        return response()->json($main);
    }
}
