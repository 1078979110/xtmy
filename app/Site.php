<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Site extends Model
{
    //
    protected $talbe='siteinfo';
    protected $fillable = [];
    public function setBannersAttribute($Banners)
    {
        if (is_array($Banners)) {
            $this->attributes['banners'] = json_encode($Banners);
        }
    }
    
    public function getBannersAttribute($Banners)
    {
        if(is_null(json_decode($Banners))){
            return [];
        }else{
            return json_decode($Banners, true);
        }
    }

    public static function getWareHouse($id){
        return DB::table('admin_users')->where('id', $id)->first();
    }

    public static function getWareHouseForSelect(){
        return  DB::table('admin_users')
            ->leftJoin('admin_role_users', 'user_id','=','id')
            ->where('role_id','=',8)
            ->pluck('name', 'id');

    }
}
