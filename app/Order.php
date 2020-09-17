<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Order extends Model
{
    //
    protected $table='orders';
    protected $fillable = [];
    
    public function users(){
        return $this->belongsTo(Salelist::class);
    }

    public static function orderMedicinals($orderid){
        return DB::table('order_medicinals')->where('order_id',$orderid)->get()->toArray();
    }

    public static function orderGift($orderid){
        return DB::table('order_gift')->where('order_id',$orderid)->get()->toArray();
    }

    public static function medicinalInfo($id){
        return DB::table('medicinal')->where('id', $id)->first();
    }

    public static function hasDiaoDu($orderid, $userid){
        return DB::table('orders_diaodu')->where([['order_id', $orderid],['warehouse_id', $userid]])->exists();
    }
    public static function diaodu($orderid, $userid){
        return DB::table('orders_diaodu')->where([['order_id', $orderid],['warehouse_id', $userid]])->get();
    }
}
