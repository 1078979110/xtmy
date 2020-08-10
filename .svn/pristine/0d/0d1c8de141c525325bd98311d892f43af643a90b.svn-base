<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table='orders';
    protected $fillable = [];
    
    public function users(){
        return $this->belongsTo(Salelist::class);
    }
}
