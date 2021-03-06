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

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleted(function($model){
            $has = Category::where('line_id', $model->id)->exists();
            if($has){
                throw new \Exception('请先清空产品线下的分类再来操作!');
            }
        });
    }
}
