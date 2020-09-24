<?php
namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use App\Hospital;
use App\Order;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Facades\Admin;
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
        $id = $request->id;
        //$info = Order::where('id',$request->get('id'))->value('orderinfo');
        $info = DB::table('order_medicinals')->where('order_id', $id)->get()->toArray();
        foreach ($info as $key=>$val){
            $medicinal = DB::table('medicinal')->where('id', $val->medicinal_id)->first();
            $info[$key]->medicinal = $medicinal->medicinal;
            $info[$key]->medicinalnum = $medicinal->medicinalnum;
            $info[$key]->unit = $medicinal->unit;
        }
        $content->body(view('admin.extension.changeprice',['info'=>$info, 'id'=>$request->get('id')])->render());
        return $content;
    }

    public function updateAttr(Content $content){
        $content->title('补充信息');
        $request = request();
        $id = $request->id;
        $user_id = Admin::user()->id;
        $infos = DB::table('order_fenpi')->where([['order_id', $id],['warehouse_id', $user_id]])->get();
        if(empty($infos->toArray())){
            admin_toastr('未执行分批操作,无法补充信息','warning');
            return redirect('/admin/orders');
        }
        foreach ($infos as $key=>$info){
            $medicinal = DB::table('medicinal')->find($info->medicinal_id);
            $order_medicinals = DB::table('order_medicinals')->where([['order_id', $id],['medicinal_id', $info->medicinal_id]])->first();
            $infos[$key]->medicinal = $medicinal->medicinal;
            $infos[$key]->medicinalnum = $medicinal->medicinalnum;
            $infos[$key]->unit = $medicinal->unit;
            $infos[$key]->num = $info->num;
            $infos[$key]->price = $order_medicinals->price;
            $infos[$key]->batchnumber = empty($info->batchnumber)?'':$info->batchnumber;
            $infos[$key]->invalidate = empty($info->invalidate)?'':$info->invalidate;
            $infos[$key]->makedate = empty($info->makedate)?'':$info->makedate;
            $infos[$key]->boxformat = empty($info->boxformat)?'':$info->boxformat;
            $infos[$key]->novirus = empty($info->novirus)?'':$info->novirus;
            $infos[$key]->originmake = empty($info->originmake)?'':$info->originmake;
            $infos[$key]->tips = empty($info->tips)?'':$info->tips;
        }
        $content->body(view('admin.order.updateattr', ['products'=>$infos,'id'=>$id])->render());
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

    public function splitOrder(Content $content){
        $content->title('订单调度');
        $request = request();
        $id= $request->id;
        $orderinfo = DB::table('order_medicinals')->where('order_id', $id)->get();
        foreach ($orderinfo as $key => $info){
            $medicinal = DB::table('medicinal')->where('id', $info->medicinal_id)->first();
            $orderinfo[$key]->medicinal = $medicinal->medicinal;
            $orderinfo[$key]->medicinalnum = $medicinal->medicinalnum;
            $hasgift = DB::table('order_gift')->where([['order_id', $id],['origin_id', $info->medicinal_id]])->exists();
            if($hasgift){
                $orderinfo[$key]->hasgift = true;
            }else{
                $orderinfo[$key]->hasgift = false;
            }
        }
        $ordergift = DB::table('order_gift')->where('order_id', $id)->get();
        foreach ($ordergift as $key =>$gift){
            $medicinal = DB::table('medicinal')->where('id', $info->medicinal_id)->first();
            $origininfo = DB::table('medicinal')->where('id', $gift->origin_id)->first();
            $ordergift[$key]->medicinal = $medicinal->medicinal;
            $ordergift[$key]->medicinalnum = $medicinal->medicinalnum;
            $ordergift[$key]->origin = $origininfo->medicinal.'['.$origininfo->medicinalnum.']';
        }
        $warehouses = DB::table('admin_users')
            ->leftJoin('admin_role_users','admin_role_users.user_id','admin_users.id')
            ->where('admin_role_users.role_id',8)
            ->get(['id','username','name'])->toArray(true);
        $diaodu = DB::table('orders_diaodu')->where('order_id',$id)->get(['id','medicinal_id', 'num', 'warehouse_id'])->toArray(true);
        $content->body(view('admin.order.diaodu',
            ['id'=>$id,'medicinals'=>$orderinfo->toArray(), 'medicinals_json'=>$orderinfo->toJson(),
                'warehouses'=>$warehouses, 'warehouses_json'=>json_encode($warehouses),'diaodu'=>$diaodu,
                'gifts'=> $ordergift->toArray(true)
            ]
        )->render());
        return $content;
    }

    public function fenpiOrder(Content $content){
        $content->title('订单分批');
        $request = request();
        $id = $request->id;
        $user_id = Admin::user()->id;
        $orderinfo = DB::table('orders_diaodu')->where([['order_id',$id],['warehouse_id', $user_id]])->get();
        $hasfenpi = $orderinfo->toArray();
        if(empty($hasfenpi)){
            admin_toastr('暂无分配子订单，无法执行分批操作','warning');
            return redirect('/admin/orders');
        }
        foreach ($orderinfo as $key => $info){
            $medicinal = DB::table('medicinal')->where('id', $info->medicinal_id)->first();
            $orderinfo[$key]->medicinal = $medicinal->medicinal;
            $orderinfo[$key]->medicinalnum = $medicinal->medicinalnum;
        }
        $fenpi = DB::table('order_fenpi')->where([['order_id',$id],['warehouse_id', $user_id]])->get(['id','medicinal_id', 'num', 'warehouse_id'])->toArray(true);
        $content->body(view('admin.order.fenpi',[
            'id'=>$id,'medicinals'=>$orderinfo->toArray(), 'medicinals_json'=>$orderinfo->toJson(),'fenpi'=>$fenpi
        ])->render());
        return $content;
    }

    public function startShipping(Content $content){
        $content->title('开始发货');
        $request = request();
        $id = $request->id;
        $user_id = Admin::user()->id;
        $orderinfos = DB::table('order_fenpi')->where([['order_id', $id],['warehouse_id', $user_id]])->get();

        if(empty($orderinfos->toArray(true))){
            admin_toastr('暂无分配子订单，无法执行分批操作','warning');
            return redirect('/admin/orders');
        }
        $products = [];
        foreach ($orderinfos as $key=>$val){
            $medicinalinfo = DB::table('medicinal')->find($val->medicinal_id);
            $ordermedicinals = DB::table('order_medicinals')->where([['order_id', $id],['medicinal_id', $val->medicinal_id]])->first();
            $products[] = [
                'medicinal' => $medicinalinfo->medicinal,
                'medicinalnum' => $medicinalinfo->medicinalnum,
                'num' => $val->num,
                'batchnumber' => $val->batchnumber,
                'boxformat' => $val->boxformat,
                'novirus' => $val->novirus,
                'makedate' => $val->makedate,
                'invalidate' => $val->invalidate,
                'originmake' => $val->originmake,
                'tips'=> $val->tips
            ];
        }
        $orderid = DB::table('orders')->where('id', $id)->value('orderid');
        $content->body(view('admin.order.shipping',
            ['id'=>$id, 'products'=>$products, 'orderid'=>$orderid,'username'=>Admin::user()->username]
        )->render());
        return $content;
    }
}