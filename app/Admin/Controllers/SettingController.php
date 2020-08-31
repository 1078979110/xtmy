<?php
namespace App\Admin\Controllers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Facades\Admin;
use App\Salelist;
use Illuminate\Support\Facades\DB;
use App\Prints;
class SettingController extends AdminController{
    public function info(Content $content){
        $content->title('账号设置');
        $telephone = Admin::user()->username; 
        $info = Salelist::where('telephone',$telephone)->get()->toArray(true);
        $info[0]['username'] = $info[0]['telephone'];
        if(empty($info)){
            $infos = DB::table('admin_users')->where('username', $telephone)->get()->toArray(true);
            $info = (array)$infos[0];
        }
        $curr_info = $info;
        $content->body(view('admin.setting.info',['info'=>$curr_info])->render());
        //$content->body($form);
        return $content;
    }
    
    public function selectPrint(Content $content){
        
    }
}