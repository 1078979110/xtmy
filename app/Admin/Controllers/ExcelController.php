<?php
namespace App\Admin\Controllers;

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
            $infos[$key]['tips'] = empty($info['tips'])?'':$info['tips'];
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

    public function diaoDu(Content $content){
        $content->title('订单调度');
        $request = request();
        $id= $request->id;
        $orderinfo = DB::table('orders')->where('id', $id)->value('orderinfo');
        $warehouses = DB::table('admin_users')
            ->leftJoin('admin_role_users','admin_role_users.user_id','admin_users.id')
            ->where('admin_role_users.role_id',8)
            ->get(['id','username'])->toArray(true);
        $medicinals = json_decode($orderinfo, true);
        $diaodu = DB::table('orders_diaodu')->where('orderid',$id)->get(['id','medicinalid', 'num', 'warehouseid'])->toArray(true);

        $content->body(view('admin.order.diaodu',
            ['id'=>$id,'medicinals'=>$medicinals, 'medicinals_json'=>$orderinfo,'warehouses'=>$warehouses, 'warehouses_json'=>json_encode($warehouses),'diaodu'=>$diaodu]
        )->render());
        return $content;
    }
}