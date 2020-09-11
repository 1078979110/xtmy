<?php
namespace App\Admin\Controllers;

use App\Medicinal;
use App\Producer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use App\Hospital;
use App\Order;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Form;
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
        $form->file('excel','数据源')->rules('mimes:xls,xlsx')->move(env('APP_URL').'/upload/')
            ->required()->help('必须包含器械名称，产品货号，厂家，产品线和产品分类列');
        /*$form->select('producer_id','厂家')->options(function(){
            return Producer::pluck('name','id');
        })->load('line_id','/admin/api/line')->required();
        $form->select('line_id','产品线')->load('category_id','/admin/api/category')->required();
        $form->select('category_id','产品分类')->required();*/
        $content->body($form);
        return $content;
    }
    
    public function setPirce(Content $content){
        $content->title('价格导入');
        $form = new \Encore\Admin\Widgets\Form();
        
        $form->action('/admin/api/setprice');
        $form->file('excel','数据源')->rules('mimes:xls,xlsx')->move(env('APP_URL').'/upload/')->help('必须包含产品货号和价格两列')->required();
        $form->select('hospitalid','医院')->options(function(){
            return Hospital::pluck('hospital','id');
        })->required();
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

    public function updateAttr(Content $content){
        $content->title('补充信息');
        $request = request();
        $oid = $request->id;
        $orderinfo = Order::where('id',$oid)->value('orderinfo');
        $products = [];
        $infos = json_decode($orderinfo, true);
        foreach ($infos as $key=>$info){
            $infos[$key]['batchnumber'] = empty($info['batchnumber'])?'':$info['batchnumber'];
            $infos[$key]['invalidate'] = empty($info['invalidate'])?'':$info['invalidate'];
            $infos[$key]['makedate'] = empty($info['makedate'])?'':$info['makedate'];
            $infos[$key]['boxformat'] = empty($info['boxformat'])?'':$info['boxformat'];
            $infos[$key]['novirus'] = empty($info['novirus'])?'':$info['novirus'];
            $infos[$key]['originmake'] = empty($info['originmake'])?'':$info['originmake'];
        }
        $content->body(view('admin.order.updateattr', ['products'=>$infos,'id'=>$oid])->render());
        return $content;
    }
    
    public function order(Content $content){
        $content->title('订单导入');
        $form = new Form();
        $form->action('/admin/api/orders');
        $form->file('file', '订单excel')->rules('mimes:xls,xlsx')->required();
        $content->body($form);
        return $content;

    }
}