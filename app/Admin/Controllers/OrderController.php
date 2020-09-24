<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\OrderImport;
use App\Medicinal;
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
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('orderid','订单号');
            $filter->where(function($query){
                $buyerid = Salelist::where('name',$this->input)->value('id');
                $query->where('buyerid',$buyerid);
            },'下单人');
            $stat = ['1'=>'待确认','3'=>'待报价','4'=>'待审核','5'=>'待发货','6'=>'已出库','7'=>'已完成'];
            $filter->equal('orderstatus','订单状态')->select($stat);
            $arr = ['1'=>'经销商','2'=>'医院'];
            $filter->equal('buyertype','订单类型')->select($arr);
        });
        $grid->disableRowSelector();
        $grid->disableCreateButton();
        $grid->disableColumnSelector();
        $grid->disableExport();
        if(Admin::user()->isRole('administrator') || Admin::user()->isRole('finance')){
            $grid->disableRowSelector(false);
            $grid->disableExport(false);
        }
        if(!Admin::user()->isRole('administrator')){
            $grid->batchActions(function($batch){
                $batch->disableDelete();
            });
        }
        /*if(Admin::user()->isRole('warehouse')){
            $grid->model()->where('orderstatus','>=',5);
        }*/
        $grid->model()->orderBy('id','desc');
        $grid->column('orderid','订单号');
        $grid->column('totalprice', '订单金额');
        $grid->column('orderinfo','订单详情')->display(function(){
            return '<button class="btn btn-primary btn-xs">查看</button>';
        })->modal('订单内容',function() {
            $orderinfo = Order::orderMedicinals($this->id);
            if ($orderinfo){
                $tp = 0;
                $sarr = [];
                foreach ($orderinfo as $key => $val) {
                    $medicinal = Order::medicinalInfo($val->medicinal_id);
                    $sarr[$key]['id'] = $key + 1;
                    $sarr[$key]['medicinal'] = $medicinal->medicinal;
                    $sarr[$key]['medicinalnum'] = $medicinal->medicinalnum;
                    $sarr[$key]['price'] = $val->price?$val->price:'0.00';
                    $sarr[$key]['unit'] = $medicinal->unit;
                    $sarr[$key]['num'] = $val->num;
                    $sarr[$key]['price_t'] = $val->num* $val->price;
                    $tp += $sarr[$key]['price_t'];
                }
                $sarr[] = ['', '', '', '', '', '<b>总计</b>', '<b>' . $tp . '</b>'];
                return new Table(['id', '药品名称', '产品货号', '单价', '单位', '数量', '小计'], $sarr);
            }
        });
        $grid->column('buyerid','下单人')->display(function($buyerid){
            return Salelist::where('id',$buyerid)->value('name');
        });
        $grid->column('hospital','医院')->display(function($hospital) {
            return Hospital::where('id',$hospital)->value('hospital');
        });
        $grid->column('赠品内容')->display(function(){
            $gift_arr = Order::orderGift($this->id);
            if(!$gift_arr){
                return '无赠品';
            }else{
                return '<button class="btn btn-primary btn-xs">查看</button>';
            }
        })->modal('赠品内容',function(){
            $gift_arr = Order::orderGift($this->id);
            if($gift_arr){
                $s = [];
                foreach ($gift_arr as $key=>$val){
                    $medicinalinfo = Medicinal::where('id', $val->medicinal_id)->first();
                    $s[$key]['id'] = $key+1;
                    $s[$key]['medicinal'] = $medicinalinfo->medicinal;
                    $s[$key]['medicinalnum'] = $medicinalinfo->medicinalnum;
                    $s[$key]['num'] = $val->num;
                    if(isset($val->origin_id)){
                        $origin = Medicinal::where('id', $val->origin_id)->first();
                        $s[$key]['origin'] = $origin->medicinal.'['.$origin->medicinalnum.']';
                    }
                }
                return new Table(['ID','赠品名称','赠品货号', '赠品数量','赠品来源'],$s);
            }
        });

        if(Admin::user()->isRole('warehouse')){
            $grid->column('所属子订单')->display(function(){
                $has = Order::hasDiaoDu($this->id, Admin::user()->id);
                if($has){
                    return '查看';
                }else{
                    return '暂无';
                }
            })->modal('子订单内容', function(){
                $orders = Order::diaodu($this->id, Admin::user()->id);
                $order_arr = [];
                if(!empty($orders)){
                    foreach ($orders as $key => $val){
                        $medicinal = Medicinal::find($val->medicinal_id);
                        $_d = [
                            'key'=> $key+1,
                            'medicinal' => $medicinal->medicinal,
                            'medicinalnum' => $medicinal->medicinalnum,
                            'unit' => $medicinal->unit,
                            'num' => $val->num
                        ];
                        $gift = Order::getGiftByOriginId($this->id, Admin::user()->id,$val->medicinal_id);
                        if(!empty($gift)){
                            $giftmedicinal = Medicinal::find($gift->medicinal_id);
                            $_d['giftname'] = $giftmedicinal->medicinal;
                            $_d['giftmedicinal'] = $giftmedicinal->medicinalnum;
                            $_d['giftnum'] = $gift->num;
                        }else{
                            if($this->buyertype ==2){
                                $_d['giftname'] = '无';
                                $_d['giftmedicinal'] = '无';
                                $_d['giftnum'] = '无';
                            }
                        }
                        $order_arr[] = $_d;
                    }
                    if($this->buyertype ==2){
                        return new Table(['Id','名称', '产品货号','单位','数量','赠品名称', '赠品货号','赠品数量'], $order_arr);
                    }else{
                        return new Table(['Id','名称', '产品货号','单位','数量'], $order_arr);
                    }
                }
            });
            $grid->column('操作状态')->display(function(){
                $has = Order::hasFenPi($this->id, Admin::user()->id);
                if($has){
                    return '已分批';
                }else{
                    return '暂无';
                }
            })->modal('操作状态', function(){
                $fenpis = Order::fenpi($this->id, Admin::user()->id);
                $fenpi_arr = [];
                foreach ($fenpis as $key=>$fenpi){
                    $medicinalinfo = Medicinal::find($fenpi->medicinal_id);
                    $fenpi_arr[$key]['key'] = $key+1;
                    $fenpi_arr[$key]['medicinal'] = $medicinalinfo->medicinal;
                    $fenpi_arr[$key]['medicinalnum'] = $medicinalinfo->medicinalnum;
                    $fenpi_arr[$key]['num'] = $fenpi->num;
                    $fenpi_arr[$key]['batchnumber'] = $fenpi->batchnumber;
                    $fenpi_arr[$key]['novirus'] = $fenpi->novirus;
                    $fenpi_arr[$key]['makedate'] = $fenpi->makedate;
                    $fenpi_arr[$key]['invalidate'] = $fenpi->invalidate;
                    $fenpi_arr[$key]['boxformat'] = $fenpi->boxformat;
                    $fenpi_arr[$key]['originmake'] = $fenpi->originmake;
                    $fenpi_arr[$key]['tips'] = $fenpi->tips;
                    $fenpi_arr[$key]['status'] = ($fenpi->status == 1)?'<font color="red">已发货</font>':'未发货';
                }
                return new Table(['Id','名称', '货号', '数量', '批号', '灭菌批号', '生产日期', '失效日期', '装箱规格', '原产地', '备注/说明', '状态'], $fenpi_arr);
            });
        }
        $grid->column('created_at','下单时间');
        $grid->column('orderstatus','订单状态')->display(function(){
            $str = '';
            $button_ = ['待确认','待报价','待报价','待审核','待发货','已出库','已完成'];
            if ($this->orderstatus == 1) {
                $str = '<button class="btn btn-default btn-xs" style="background-color: #932ab6; border-color:#932ab6; color: white">' . $button_[$this->orderstatus - 1] . '</button>';
            }else if($this->orderstatus == 3){
                $str = '<button class="btn btn-warning btn-xs" style="background-color: greenyellow; border-color:greenyellow; color: black">' . $button_[$this->orderstatus - 1] . '</button>';
            }else if($this->orderstatus == 4){
                $str = '<button class="btn btn-danger btn-xs" style="background-color: gold; border-color:gold; color: black">' . $button_[$this->orderstatus - 1] . '</button>';
            }else if($this->orderstatus == 5){
                $str = '<button class="btn btn-primary btn-xs" style="background-color: orangered; border-color: orangered">' . $button_[$this->orderstatus - 1] . '</button>';
            }else if($this->orderstatus == 6){
                $str = '<button class="btn btn-info btn-xs">' . $button_[$this->orderstatus - 1] . '</button>';
            }else{
                $str = '<button class="btn btn-success btn-xs" style="background-color: #985f0d; border-color:#985f0d">'.$button_[$this->orderstatus-1].'</button>';
            }
            return $str;
        });
        if(!Admin::user()->isRole('administrator')){
            $grid->column('订单操作')->display(function(){
                $str = '';
                $button_ = ['待确认','待报价','待报价','待审核','待发货','已出库','已完成'];
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
                $(".fenpi").click(function(){
                    id = $(this).attr('data-id');
                    window.location.href='/admin/excel/fenpi?id=' +id;
                });
                $(".splitorder").click(function(){
                    id = $(this).attr('data-id');
                        window.location.href='/admin/excel/splitorder?id='+id;
                    });
                $(".updateattr").click(function(){
                    id = $(this).attr('data-id');
                    window.location.href='/admin/excel/updateattr?id='+id;
                });
                $(".shipping").click(function(){
                    id = $(this).attr('data-id');
                    window.location.href='/admin/excel/shipping?id='+id;
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
                $(".zhuanyun").click(function(){
                    id = $(this).attr('data-id');
                    window.location.href='/admin/print/jxs?id='+id+'&zhuanyun=1';
                });
                $(".foc").click(function(){
                    id = $(this).attr('data-id');
                    window.location.href='/admin/print/jxs?id='+id+'&foc=1';
                });
                $(".over").click(function(){
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
               
EOT;
                if(Admin::user()->isRole('wholesale') && $this->orderstatus == 3){
                    $str = '<button class="btn btn-warning btn-xs comfirmorder" data-id="'.$this->id.'">确认报价</button> | ';
                    //if($this->splitstatus == 0){
                     $str .= '<button class="btn btn-danger btn-xs splitorder" data-id="'.$this->id.'">分库</button> | ';
                    //}
                    $str .= '<button class="btn btn-danger btn-xs changeprice" data-id="'.$this->id.'">改价</button>';
                }else if(Admin::user()->isRole('finance')){
                    if($this->orderstatus == 4){
                        $str = '<button class="btn btn-info btn-xs comfirmorder" data-id="'.$this->id.'">确认收款</button>';
                    }else if($this->orderstatus == 6){
                        $str = '<button class="btn btn-warning btn-xs print" data-type="'.$this->buyertype.'" data-id="'.$this->id.'">打印出货单</button>';
                    }
                }else if(Admin::user()->isRole('warehouse')){
                    if($this->orderstatus == 5){
                        $str = '<button class="btn btn-primary btn-xs fenpi" data-id="'.$this->id.'">产品分批</button> | 
                                <button class="btn btn-info btn-xs updateattr" data-id="'.$this->id.'">补充信息</button> | 
                                <button class="btn btn-success btn-xs print" data-type="'.$this->buyertype.'" data-id="'.$this->id.'">打印出货单</button> | ';
                        if($this->buyertype ==1){
                            $str .= '<button class="btn btn-warning btn-xs zhuanyun" data-id="'.$this->id.'">转运证明</button> | 
                                    <button class="btn btn-warning btn-xs foc" data-id="'.$this->id.'">销售订单/FOC申请表</button> | ';
                        }
                            $str .= '<button class="btn btn-warning btn-xs shipping" data-id="'.$this->id.'">确认发货</button>';
                    }/*else if($this->orderstatus == 6){
                        $str = '<button class="btn btn-warning btn-xs shipping" data-id="'.$this->id.'">已发货</button> | ';
                    }*/
                    //$str .= '<button class="btn btn-danger btn-xs over" data-id="'.$this->id.'">确认完成</button>';
                }
                Admin::script($js);
                return $str;
            });
        }
        $grid->export(function($export){
            $export->only(['orderid', 'totalprice' ,'buyerid', 'hospital','created_at']);
            $export->column('orderid', function($value, $original){
                return '\''.$value;

            });
        });
        $grid->tools(function($tools){
            //if(Admin::user()->isRole('administrator')) //批量导入经销商订单
            //$tools->append(new OrderImport());
        });
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

        return $form;
    }
}
