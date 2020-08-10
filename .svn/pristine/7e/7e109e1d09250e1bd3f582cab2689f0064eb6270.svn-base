<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use App\Salelist;
use App\Hospital;
use Illuminate\Support\Facades\Session;
class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $myCart = [];//购物车
    public $authUser;
    public $userInfo;
    public $hospital;
    public $hid;//被选择的医院ID，只针对业务员来说
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next){
            $this->authUser = $this->getAuthUser();
            $this->userInfo = $this->getUserInfo();
            session()->put('user.info',$this->userInfo);
            return $next($request);
        });
        if($this->userInfo['status'] == 1){//该用户已被冻结，无法登录
            $this->loginOut();
        }
    }
    
    //获取登陆用户认证信息
    protected function getAuthUser(){
        return  Auth::user();
    }
    
    //获取登陆用户详细信息
    protected function getUserInfo(){
        return   Salelist::find($this->authUser['id'])->toArray(true);
    }
    
    protected function loginOut(){
        Auth::logout();
        session('user','');
    }
    
    protected function getHospital($hospitalid){
        return Hospital::find($hospitalid);
    }
}