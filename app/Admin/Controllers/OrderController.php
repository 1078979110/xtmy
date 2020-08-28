<?php

namespace App\Admin\Controllers;

use App\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Salelist;
use App\Hospital;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Widgets\Table;
use App\User;
use Encore\Admin\Layout\Content;
class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());
        $user_id = Admin::user()->id;
        $username = DB::table('admin_users')->where('id',$user_id)->value('username');
        $buyerid = Salelist::where('telephone',$username)->value('id');
        $user_roles = Admin::user()->roles->toArray();
        if($user_roles[0]['id'] ==4 || $user_roles[0]['id'] ==5){//如果是销售人员（包括经销商）则只能查询自己名下的订单，其他分组不受限制
            $grid->model()->where('buyerid',$buyerid);
        }
        /*
        $grid->quickSearch(function($model, $query) use ($user_roles, $buyerid){
            if($query == '医院'){
                $type = 2;
            }elseif($query == '经销商'){
                $type = 1;
            }
            $hospitalid = Hospital::where('hospital','like','%'.$query.'%')->value('id');
            $bid = Salelist::where('name','like','%'.$query.'%')->orWhere('telephone',$query)->value('id');
            if(isset($type)){//订单类型
                $model->where('buyertype',$type);
            }else if($hospitalid !=''){//医院
                $model->where('hospital', $hospitalid);
            }else if($bid != ''){//下单人或下单人电话
                $model->where('buyerid',$bid);
            }else{//订单号
                $model->where('orderid','like','%'.$query.'%');
            }
        }); */
        
        $grid->filter(function($filter)use($user_roles){
            $filter->disableIdFilter();
            $filter->like('orderid','订单号');
            /*$filter->where(function($query){
                $buyerid = Salelist::where('telephone',$this->input)->value('id');
                $query->where('buyerid',$buyerid);
            },'手机号');*/
            $filter->where(function($query){
                $buyerid = Salelist::where('name',$this->input)->value('id');
                $query->where('buyerid',$buyerid);
            },'名称');
            if($user_roles[0]['id'] !=4 && $user_roles[0]['id'] !=5){
                $stat = ['1'=>'待确认','2'=>'待报价(经销商)','3'=>'待报价','4'=>'待审核','5'=>'待发货','6'=>'已发货'];
                $filter->equal('orderstatus','订单状态')->select($stat);
                $arr = ['1'=>'经销商','2'=>'医院'];
                $filter->equal('buyertype','订单类型')->select($arr);
            }
        });
        $grid->model()->orderBy('created_at','desc');
        $grid->column('orderid','订单号');
        $grid->column('totalprice', '订单金额');
        $grid->column('orderinfo','订单详情')->display(function(){
            return '<button class="btn btn-primary btn-xs">查看</button>';
        })->modal('订单内容',function(){
            $arr = json_decode($this->orderinfo, true);
            $tp = 0;
            foreach ($arr as $key =>$val){
                $arr[$key]['id'] = $key+1;
                $arr[$key]['price_t'] = $val['num']*$val['price'];
                $tp += $arr[$key]['price_t'];
            }
            $arr[] = ['','','','','','','<b>总计</b>','<b>'.$tp.'</b>'];
            return new Table(['id','药品名称','产品编号','规格','单位','数量','单价','小计'], $arr);
        });
        
        $grid->column('buyerid','下单人')->display(function($buyerid){
            return Salelist::where('id',$buyerid)->value('name');
        });
        if($user_roles[0]['id'] != 5){
            $grid->column('hospital','医院')->display(function($hospital) {
                return Hospital::where('id',$hospital)->value('hospital');
            });
            $grid->column('赠品内容')->display(function($gift)use($user_roles){
                if(empty($this->gift)){
                    return '无赠品';
                }else{
                  return '<button class="btn btn-primary btn-xs">查看</button>';
                }
            })->modal('赠品内容',function(){
                if(!empty($this->gift)){
                    $gift_arr = json_decode($this->gift, true);
                    foreach ($gift_arr as $key=>$val){
                        $gift_arr[$key]['id'] = $key+1;
                    }
                    return new Table(['ID','名称','规格', '数量'],$gift_arr);
                }
            });
                $grid->column('gift','赠品')->display(function($gift)use($user_roles){
                if($this->orderstatus ==1){
                    if($user_roles[0]['id'] == 4){
                        $js = <<<EOT
                            $(".gift").click(function(){
                                id = $(this).attr('data-id');
                                window.location.href="/admin/api/gifts?id="+id
                            });
EOT;
                        Admin::script($js);
                        return '<span><button class="btn btn-primary btn-xs gift" data-id="'.$this->id.'">去设置</button></span>';
                    }
                }
            });
        }
        $grid->column('created_at','下单时间');
        $grid->column('orderstatus','订单状态')->display(function($orderstatus) use($user_roles){
            $js = <<<EOT
            $(".comfirmorder").click(function(){
                id = $(this).attr('data-id');
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    url:'/admin/api/changestatus',
                    method:'post',
                    data:{'id':id},
                    success:function(res){
                        if(res.status){        
                                toastr.success(res.msg,res.title,setTimeout(function (){window.location.reload();}, 4000))
                            }else{
                                toastr.warning(res.msg,res.title,setTimeout(function (){window.location.reload();}, 4000))
                        }
                    }
                });
            });
            $(".changeprice").click(function(){
                id = $(this).attr('data-id');
                window.location.href='/admin/excel/changeprice?id='+id;
            });
            $(".print").click(function(){
                id = $(this).attr('data-id');
                type = $(this).attr('data-type');
                if(type ==1){
                    window.location.href='/admin/print/jxs?id='+id;
                }else{
                window.location.href='/admin/print/hostpital?id='+id;
                }
            });
EOT;
            $str = '';
            $button_ = ['待确认','待报价','待报价','待审核','待发货','已完成','已完成'];
            if($user_roles[0]['id'] == 4){//业务员组
                if($orderstatus ==1){
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button> | <button class="btn btn-warning btn-xs comfirmorder" data-id="'.$this->id.'">确认订单</button>';
                }else{
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
                }
            }else if($user_roles[0]['id'] == 5){//经销商组
                if($orderstatus ==2){
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
                }else{
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
                }
            }else if($user_roles[0]['id'] == 6){//批发部
                if($orderstatus == 3 || $orderstatus ==2){
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button> | <button class="btn btn-warning btn-xs comfirmorder" data-id="'.$this->id.'">确认报价</button> | <button class="btn btn-danger btn-xs changeprice" data-id="'.$this->id.'">改价</button>';
                }else{
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
                }
            }else if($user_roles[0]['id'] == 7){//财务部
                if($orderstatus ==4){
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button> | <button class="btn btn-warning btn-xs comfirmorder" data-id="'.$this->id.'">确认收款</button>';
                }else{
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
                }
            }else if($user_roles[0]['id'] == 8){//仓库
                if($orderstatus ==5){
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button> | <button class="btn btn-warning btn-xs comfirmorder" data-id="'.$this->id.'">确认发货</button>';
                }else if( $orderstatus == 6 ){//确定发货，打印出货单
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button> | <button class="btn btn-warning btn-xs print" data-type="'.$this->buyertype.'" data-id="'.$this->id.'">打印出货单</button>';
                }else{
                    $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
                }
            }else{
                $str = '<button class="btn btn-primary btn-xs">'.$button_[$orderstatus-1].'</button>';
            }
            Admin::script($js);
            return $str;
        });
        $grid->disableCreateButton();
        $grid->disableColumnSelector();
        $grid->disableActions();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());
        $form->text('gift','赠品');
        $form->table('orderinfo', '订单详情', function($table){
            $table->text('id');
            $table->text('medicinal');
            $table->text('medicinalnum');
            $table->text('num');
            $table->text('price');
        });

        return $form;
    }
}
