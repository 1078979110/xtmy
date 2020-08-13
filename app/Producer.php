<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    //
    protected $table='producer';
    public static function getProducerNameById($id){
        $name = Producer::where('id',$id)->value('name');
        return $name;
    }
}
