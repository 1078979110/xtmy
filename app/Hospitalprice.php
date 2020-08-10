<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospitalprice extends Model
{
    //
    protected $table = 'hospitalprice';
    protected $fillable = ['hospitalid','medicinalid','price'];
}
    