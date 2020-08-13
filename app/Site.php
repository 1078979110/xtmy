<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return json_decode($Banners, true);
    }
}
