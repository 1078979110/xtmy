<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usertype;
class Salelist extends Model
{
    //
    protected $table='users';
    protected $fillable=['name','telephone','password','updated_at','created_at'];
    public function getTypeNameByTypeId($id){
        return Usertype::where('id',$id)->value('usertype');
    }
    
    public function getTypeIdName(){
        return Usertype::orderBy('id','asc')->pluck('usertype','id');
    }

}
