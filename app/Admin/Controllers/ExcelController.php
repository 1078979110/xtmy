<?php
namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Admin;
use Illuminate\Support\Facades\Redirect;
use App\Hospital;
use App\Order;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Table;
class ExcelController extends AdminController{
    public function excel(Content $content){
        $url = $_SERVER['HTTP_REFERER'];
        $arr = explode('/', $url);
        $func = $arr[sizeof($arr)-1];
        return redirect('/admin/excel/'.$func);
    }
    public function medicinals(Content $content){
        $content->title('药品导入');
        $form = new \Encore\Admin\Widgets\Form();
        $form->action('/admin/api/medicinals');
        $form->file('excel','数据源')->rules('mimes:xls,xlsx')->move(env('APP_URL').'/upload/');
        $content->body($form);
        return $content;
    }
    
    public function setPirce(Content $content){
        $content->title('价格导入');
        $form = new \Encore\Admin\Widgets\Form();
        
        $form->action('/admin/api/setprice');
        $form->file('excel','数据源')->rules('mimes:xls,xlsx')->move(env('APP_URL').'/upload/');
        $form->select('hospitalid','医院')->options(function(){
            return Hospital::pluck('hospital','id');
        });
        $content->body($form);
        return $content;
    }
    
    public function changePrice(Content $content, Request $request){
        $content->title('修改价格');
        $info = Order::where('id',$request->get('id'))->value('orderinfo');
        $info = json_decode($info,true);
        //$form = new \Encore\Admin\Widgets\Form(['info'=>$info]);
        $content->body(view('admin.extension.changeprice',['info'=>$info, 'id'=>$request->get('id')])->render());
        return $content;
    }
    
    
}