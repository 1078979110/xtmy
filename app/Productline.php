<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Producer;
class Productline extends Model
{
    //
    protected $table='productlines';
    
    public function getProducerNameById($id){
        $name = Producer::where('id',$id)->value('name');
        return $name;
    }
    
    public static function getProducerIdName(){
        $list = Producer::orderBy('id', 'asc')->pluck('name','id');
        return $list;
    }
    
    public static function getLineNameById($id){
        $name = Productline::where('id',$id)->value('linename');
        return $name;
    }
}
