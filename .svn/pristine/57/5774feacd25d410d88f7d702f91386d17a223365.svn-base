<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Producer;
use App\Productline;
use App\Category;
use App\Medicinal;
use App\Hospital;
use App\Hospitalprice;
use App\Mycart;
class IndexController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $producers = Producer::get()->toArray(true);//厂家
        $productlines = Productline::get()->toArray(true);//产品线
        $categories = Category::get()->toArray(true);
        return view('home'); 
        
    }
    
    public function indexSearch(){
        $request = request();
        $producerid = isset($request['pid'])?$request['pid']:'';
        $lineid = isset($request['lid'])?$request['lid']:'';
        $categoryid = isset($request['cid'])?$request['cid']:'';
        $q = isset($request['q'])?$request['q']:'';
        $data = [];
        $data = Medicinal::where(function($db) use($producerid, $lineid, $categoryid){
                    if($producerid != ''){
                        $db->where('producer_id' , $producerid)->where(function($db) use($lineid, $categoryid){
                            if($lineid != ''){
                                $db->where('line_id',$lineid)->where(function($db) use($categoryid){
                                    if($categoryid != ''){
                                        $db->where('category_id', $categoryid);
                                    }
                                });
                            }else{
                                if($categoryid != ''){
                                    $db->where('category_id', $categoryid);
                                }
                            }
                        });
                    }else{
                        $db->where(function($db) use($lineid, $categoryid){
                            if($lineid != ''){
                                $db->where('line_id',$lineid)->where(function($db) use($categoryid){
                                    if($categoryid != ''){
                                        $db->where('category_id', $categoryid);
                                    }
                                });
                            }else{
                                if($categoryid != ''){
                                    $db->where('category_id', $categoryid);
                                }
                            }
                        });
                    }
                })
               ->where(function($db)use($q){
                   if($q !='')
                   $db->orWhere('medicinal','like','%'.$q.'%')->orWhere('specification',$q);
               })->paginate()->toArray(true);
        if($this->userInfo['type'] == 2){//医院用获取医院价格
            foreach ((array)$data['data'] as $key => $val){
                $data[$key]['price'] = Hospitalprice::where([['hospitalid',$this->hid],['medicinalid', $val['id']]])->value('price');
            }
        }
    }
    
    //购物车
    public function myCart(){
        $userinfo = session('user.info');
        $hospitalinfo = session('user.hospital');
        if(empty($hospitalinfo)){
            $lists = Mycart::where('buyerid', $userinfo['id']);
        }else{
            $lists = Mycart::where('buyerid', $userinfo['id'])
            ->where('hospitalid',$hospitalinfo['id']);
        }
        var_dump($lists);
    }
    
    //登陆成功后立刻选择医院,针对业务员
    public function selectHospital(){
        $hid = request()->post('hid');
        $this->hospital = Hospital::find($hid);
        $this->hid = $hid;
        session()->put('user.hospital',$this->hospital);
        return json_encode(['status'=>true]);
    }

    
    public function addCart(){
        if(request()->isMethod('post')){
            $data['price'] = request()->post('price');
            $data['specification'] = request()->post('specification');
            $data['num'] = request()->post('num');
            $data['buyerid'] = $this->userInfo['id'];
            $data['medicinalid'] = request()->post('id');
            if($this->userInfo['type'] == 2){
                $data['hospitalid'] =$this->hid;
            }
            $this->myCart[] = $data;
            $result = DB::table('mycart')->insert($data);
            return $result;
        }
    }
}
