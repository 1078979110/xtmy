<?php

use Faker\Generator as Faker;
use App\Medicinal;
use App\Salelist;
use App\Order;
use App\Hospital;
use App\Hospitalprice;
$factory->define(App\Order::class, function (Faker $faker) {
    $orderid = date('Ymd',time()).rand(1000,9999);
    $ordermonth = date('Ymd',time());
    $buyerid_yw = ['2','3','6','7','8','12','14','15','17','18'];
    $buyerid_jx = ['4','5','9','10','11','13','16','19','20'];
    $type = rand(1,2);
    if($type ==1){
        $buyerid = $buyerid_jx[rand(0,8)];
        $status = 2;
        
    }else{
        $buyerid = $buyerid_yw[rand(0,9)];
        $hospital = Hospital::where('belongto',$buyerid)->value('id');
        $status = 1;
    }
    $n = rand(1,20);
    $info = [];
    $total = 0;
    for ($i = 0; $i<$n; $i++){
        $mid = rand(1395,1512);
        $minfo = Medicinal::find($mid)->toArray(true);
        $price = ($type ==1)?rand(100,999).'.'.rand(10,99):Hospitalprice::where([['medicinalid',$mid],['hospitalid',$hospital]])->value('price');
        $num = rand(10,99);
        $mminfo = [
            'id'=>$minfo['id'],
            'medicinal'=>$minfo['medicinal'],
            'medicinalnum'=>$minfo['medicinalnum'],
            'specification'=>$minfo['specification'],
            'unit' => $minfo['unit'],
            'num'=>$num,
            'price'=>$price          
        ];
        $total += $num* $price;
        $info[] = $mminfo;
    }
    return [
        //
        'orderid'=> $orderid,
        'ordermonth' => $ordermonth,
        'totalprice'=>$total,
        'orderstatus' =>$status,
        'buyerid' =>$buyerid,
        'buyertype' => $type,
        'hospital' => isset($hospital)?$hospital:null,
        'gift' =>'',
        'orderinfo' => json_encode($info)
    ];
});
