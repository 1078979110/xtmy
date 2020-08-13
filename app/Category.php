<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Productline;
class Category extends Model
{
    //
    protected $table='categories';
    public static function getLineIdNameById($id){
        $list = Productline::where('producer_id',$id)->orderBy('id', 'asc')->get(['id','linename as text']);
        return $list;
    }
    public static function getCategoryIdNameById($id){
        $list = Category::where('line_id',$id)->orderBy('id', 'asc')->get(['id','categoryname as text']);
        return $list;
    }
    
    public static function getCategoryNameById($id){
        $name = Category::where('id',$id)->value('categoryname');
        return $name;
    }
}
