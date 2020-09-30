<?php
namespace App\Admin\Controllers;

use App\Medicinal;
use App\Order;
use App\Salelist;
use App\Site;
use Encore\Admin\Controllers\AdminController;
use App\Hospital;
use App\Hospitalprice;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;
class PrintsController extends AdminController{
    public function jxsPrint(Content $content){
        $request = request();
        $id = $request->id;
        $user_id = Admin::user()->id;
        $zy = isset($request->zhuanyun)?$request->zhuanyun:0;
        $foc = isset($request->foc)?$request->foc:0;
        $orderInfo = DB::table('orders')->where('id', $id)->first();
        $buyerInfo = DB::table('users')->where('id', $orderInfo->buyerid)->first();
        $siteInfo = DB::table('sites')->find(1);
        $ext = '商品随货同行单';
        $ext2 = '商品出库单';
        $tabletitle = [];
        $datatitle = [];
        $tabletitle[0] = ['购货单位：','编号：','日期：','合计：','发货员：'];
        $tabletitle[1] = ['购货单位：','编号：','部门：','日期：','合计：','发货员：','复核员：','客户签收：'];
        $tabletitle[2] = ['购货单位：','编号：','部门：','日期：','合计：','发货员：','复核员：','客户签收：'];
        $tabletitle[3] = ['购货单位：','单据号：','地址：','日期：','合计(大写)：','总金额：','本页小计：','发货员：','复核员：','储运条件：常温库','签收：','发货地址：','联系电话：','非质量问题概不退换'];
        $tabletitle[4] = ['购货单位：','单据号：','收货地址：', '日期：', '合计（大写）：','本页小计：', '发货员：', '复核员：', '储运条件：常温库', '签收：','发货地址：','联系电话：','非质量问题概不退换'];
        $tabletitle[5] = ['购货单位：','单号：', '收货地址：', '日期：','合计（大写）：','本页小计：','发货员：', '复核员：', '储运条件：常温库', '签收：','发货地址：','联系电话：','非质量问题概不退换'];
        $datatitle[0] = ['序号','产品名称','货号','数量','单位','批号','失效日期','注册证号'];
        $datatitle[1] = ['器械名称','货号','单位','数量','单价','批号','有效期至'];
        $datatitle[2] = ['器械名称','货号','单位','数量','批号','有效期至'];
        $datatitle[3] = ['器械名称','生产商','货号','单位','数量','单价','金额','批号','注册证号','有效期至'];
        $datatitle[4] = ['器械名称', '生产厂商', '生产企业许可证', '货号', '单位','数量', '批号', '注册证号', '生产日期', '失效日期'];
        $datatitle[5] = ['器械名称', '生产厂商', '许可证号', '货号', '单位','数量', '批号', '注册证号', '生产日期', '有效期至'];;
        $financetitle = ['购货单位：','日期：', '合计（大写）：', '本页小计：',];
        $financedatatitle = ['器械名称','货号','单位','数量', '单价','金额', '备注'];
        $dataname = [];//表格各项信息
        $zhuanyun = [];
        $zhuanyun['header'] = '现有我公司今日订购一批货，特委托国药集团上海医疗器械有限公司直接发货给湖北省武汉市东西湖区金银潭大道130号临空一号企业总部2栋4 楼， 陈雷 ，电话15802715519。 请随货请附检验报告。特此证明。';
        $zhuanyun['title'] = ['产品编号','产品名称','单位','数量'];
        $zhuanyun['footer'] = $siteInfo->sitename;
        $zhuanyun['date'] = date('Y.m.d', time());
        $salefoc = [];
        $salefoc['header'] = ['客户：', '日期：', '地址：'];
        $salefoc['listtitle'] = ['产品编号','产品名称', '单位', '数量', '单价', '总价(RMB)','备注/特别说明'];
        $salefoc['listtotal'] = ['总金额（RMB）'];
        $salefoc['foctitle'] = ['FOC产品','说明','金额','总金额'];
        $salefoc['foctotal'] = ['调整后订单总金额（RMB）'];
        if(Admin::user()->isRole('warehouse')){
            $fenPi = DB::table('order_fenpi')->where([['order_id',$id],['warehouse_id', $user_id]])->get()->toArray();
        }else{
            $fenPi = DB::table('order_fenpi')->where('order_id',$id)->get()->toArray();
        }
        if(empty($fenPi)){
            admin_warning('提醒：','订单号为：'.$orderInfo->orderid.'中没有您的出库任务');
            return redirect('/admin/orders');
        }
        $data = [];
        $totalprice = 0;
        foreach ($fenPi as $key=>$value){
            //订单商品信息
            $medicinalInfo = DB::table('medicinal')->find($value->medicinal_id);
            $producer = DB::table('producer')->find($medicinalInfo->producer_id);
            $line = DB::table('productlines')->find($medicinalInfo->line_id);
            $category = DB::table('categories')->find($medicinalInfo->category_id);
            $orderMedicinals = DB::table('order_medicinals')->where([['order_id', $id],['medicinal_id', $value->medicinal_id]])->first();
            $totalprice += $value->num * $orderMedicinals->price;
            $data[$key] = [
                //基础信息
                'medicinal_id' => $value->medicinal_id,
                'medicinal' => $medicinalInfo->medicinal,
                'medicinalnum' => $medicinalInfo->medicinalnum,
                'producer' => $producer->name,
                'line' => $line->linename,
                'category' => $category->categoryname,
                'manufacturinglicense' => $medicinalInfo->manufacturinglicense,
                'manufactur' => $medicinalInfo->manufactur,
                'specification' => $medicinalInfo->specification,
                'unit' => $medicinalInfo->unit,
                'registnum' => $medicinalInfo->registnum,
                'registivalidate' => $medicinalInfo->registivalidate,
                'storagecondition'=> $medicinalInfo->storagecondition,
                //额外信息
                'price'=> $orderMedicinals->price,
                'num' => $value->num,
                'prices' => $value->num * $orderMedicinals->price,
                //补充信息
                'batchnumber' => $value->batchnumber,
                'novirus' => $value->novirus,
                'makedate' => $value->makedate,
                'invalidate' => $value->invalidate,
                'boxformat' => $value->boxformat,
                'originmake' => $value->originmake,
                'tips' => $value->tips
            ];
        }
        $totalcn = $this->get_amount($totalprice);


        $dataname[0] = [
            $tabletitle[0][0],
            $tabletitle[0][1].$orderInfo->orderid,
            $tabletitle[0][2].date('Y/m/d', time()),
            $tabletitle[0][3],
            $tabletitle[0][4]
        ];
        $dataname[1] = [
            $tabletitle[1][0],
            $tabletitle[1][1].$orderInfo->orderid,
            $tabletitle[1][2].$buyerInfo->depart,
            $tabletitle[1][3].date('Y/m/d', time()),
            $tabletitle[1][4],
            $tabletitle[1][5],
            $tabletitle[1][6],
            $tabletitle[1][7]
        ];
        $dataname[2] = $dataname[1];
        $dataname[3] = [
            $tabletitle[3][0],
            $tabletitle[3][1].$orderInfo->orderid,
            $tabletitle[3][2].$buyerInfo->address,
            $tabletitle[3][3].date('Y/m/d', time()),
            $tabletitle[3][4],
            $tabletitle[3][5],
            $tabletitle[3][6],
            $tabletitle[3][7],
            $tabletitle[3][8],
            $tabletitle[3][9],
            $tabletitle[3][10],
            $tabletitle[3][11].$siteInfo->siteaddress,
            $tabletitle[3][12].$siteInfo->telephone,
            $tabletitle[3][13]
        ];
        $dataname[4] = [
            $tabletitle[4][0],
            $tabletitle[4][1].$orderInfo->orderid,
            $tabletitle[4][2].$buyerInfo->address,
            $tabletitle[4][3].date('Y/m/d', time()),
            $tabletitle[4][4],
            $tabletitle[4][5],
            $tabletitle[4][6],
            $tabletitle[4][7],
            $tabletitle[4][8],
            $tabletitle[4][9],
            $tabletitle[4][10].$siteInfo->siteaddress,
            $tabletitle[4][11].$siteInfo->telephone,
            $tabletitle[4][12]

        ];
        $dataname[5] = [
            $tabletitle[5][0],
            $tabletitle[5][1].$orderInfo->orderid,
            $tabletitle[5][2],
            $tabletitle[5][3].date('Y/m/d', time()),
            $tabletitle[5][4],
            $tabletitle[5][5],
            $tabletitle[5][6],
            $tabletitle[5][7],
            $tabletitle[5][8],
            $tabletitle[5][9],
            $tabletitle[5][10].$siteInfo->siteaddress,
            $tabletitle[5][11].$siteInfo->telephone,
            $tabletitle[5][12]
        ];
        $financename = [
            $siteInfo->sitename.$ext2,
            $financetitle[0],
            $financetitle[1].date('Y/m/d', time()),
            $financetitle[2],
            $financetitle[3]
        ];
        $focname = [
            $salefoc['header'][0],
            $salefoc['header'][1].date('Y/m/d', time()),
            $salefoc['header'][2].$buyerInfo->address
        ];
        $totalcn = $this->get_amount($totalprice);
        if(isset($totalcn['status'])){
            admin_toastr($totalcn['msg'],'error');
            return redirect('/admin/orders');
        }
        $content->title($buyerInfo->name.$ext);

        if($zy == '1'){
            $content->title($buyerInfo->name.'转运证明');
            $content->body(view('admin.prints.zhuanyun',
                [
                    'title'=>$buyerInfo->name.'转运证明',
                    'lists'=>$data,'total'=>$totalprice,
                    'totalcn'=>$totalcn,'zhuanyun'=>$zhuanyun
                ]
            )->render());
        }else if($foc == '1'){
            $content->title($buyerInfo->name.'销售订单/FOC申请表');
            $content->body(view('admin.prints.foc',
                [
                    'title'=>$buyerInfo->name.'销售订单/FOC申请表',
                    'lists'=>$data ,'total'=>$totalprice,
                    'totalcn'=>$totalcn,'focname'=>$focname,'salefoc'=>$salefoc
                ]
            )->render());
        }else{
            $content->body(view('admin.prints.jxs',
                [
                    'title'=>$siteInfo->sitename.$ext,'tabletitle'=>$dataname,'datatitle'=>$datatitle,
                    'lists'=>$data,'jsondata'=>json_encode($data) ,'total'=>$totalprice,
                    'totalcn'=>$totalcn,'financename'=>$financename, 'financedatatitle'=>$financedatatitle, 'zhuanyun'=>$zhuanyun,
                    'buyerinfo'=>$buyerInfo
                ]
            )->render());
        }
        return $content;
    }
    
    public function hospitalPrint(Content $content){
        $ext = '商品销售单';
        $ext1 = '送货单';
        $ext2 = '医用耗材采购单';
        $sendcompany = "武汉协同贸易有限公司";
        $headername['hk'] = ['购货单位：','编号：','部门：','业务员：','开票员：','日期：','合计：','发货员：','复核员：','客户签字'];
        $headername['xzb'] =['采购订单号：','订单日期：','供应商：','收货单位：','送货人：','送货日期：','收货人：','收货日期：'];
        $headername['yx'] =['采购订单号：','订单日期：','供应商编码及名称：V30022 武汉协同贸易有限公司','收货单位：','送货人：','送货日期：','收货人：','收货日期：'];
        $headername['et'] = ['供应单位：','采购人：姚勇康','采购类型：购销','仓库：设备耗材库','订单号：','订单日期：','送货单号：','合计：','发货员：','复核员：','客户签收：'];
        $headername['tj'] = ['商业公司全称：','送货单号：','单位地址电话：','收货单位：','科室','送货日期：','合计：','收货单位及经手人签字：'];
        $headername['ds'] = ['购货单位：','开票日期：','发票号：','合计：','送货人：','收货人：','联系方式：'];
        $headername['rm'] = ['经销商：','采购日期：', '科室：','合计：'];
        $headername['fy'] = ['购货单位：','编号：','部门：', '业务员：','日期：','合计：','发货员：','复核员：','客户签收'];
        $headername['zx'] = ['购货单位：','编号：','部门：','业务员：','开票员：','日期：','合计：','发货员：','复核员：','客户签收'];
        $headername['zn'] = ['购货单位：','编号：','部门：','业务员：','开票员：','日期：','合计：','发货员：','复核员：','客户签收'];
        $headername['zy'] = ['经销商：','采购日期：','科室：湖北省紫阳医业公司','合计：'];

        $dataname['hk'] = ['器械名称','规格型号','单位','数量','单价','金额','生产日期','批号','注册证书','有效期至'];
        $dataname['xzb'] = ['序号','产品编码','产品名称','规格型号','装箱规格','生产厂家','数量','单位','单价(元)','产品批号','产品有效期','医疗器械注册证书','医疗器械注册证有效期','备注'];
        $dataname['yx'] = ['序号','产品编码','产品名称','规格型号','装箱规格','生产厂家','数量','单位','单价(元)','产品批号','产品有效期','医疗器械注册证书','医疗器械注册证有效期'];
        $dataname['et'] = ['材料名称','规格型号','单位','生产批号','有效日期','单价(元)','数量','总价(元)','生产厂家','灭菌日期'];
        $dataname['tj'] = ['产品编号','品名','规格','单位','数量','单价','金额','生产批号','有效期','备注','产地'];
        $dataname['ds'] = ['招标编号','商品名称','生产厂家','型号规格','单位','数量','单价','金额','生产批号','灭菌批号','有效期至','科室'];
        $dataname['rm'] = ['产品名称','规格型号','数量','单位','单价','金额','生产批号','灭菌批号','注册证号','产品有效期','生产厂家','原产地'];
        $dataname['fy'] = ['器械名称','规格型号','单位','数量','单价','金额','生产日期','批号','注册证号','有效期至'];
        $dataname['zx'] = ['器械名称','生产商','规格型号','单位','数量','单价','金额','生产日期','批号','注册证号','有效期至'];
        $dataname['zn'] = ['器械名称','规格型号','单位','数量','单价','金额','生产日期','批号','注册证号','有效期至'];
        $dataname['zy'] = ['产品名称','规格型号','单位','数量','包装规格','单价','金额','产品批号','灭菌批号','生产日期','产品有效期','产品注册证','注册证有效期','生产厂家'];
        //$financetitle = ['购货单位：','日期：', '合计（大写）', '本页小计：',];
        //$financedatatitle = ['器械名称','规格型号','单位','数量','金额', '备注'];

        $request = request();
        $id = $request->id;
        $user_id = Admin::user()->id;
        $orderInfo = DB::table('orders')->where('id', $id)->first();
        $buyerInfo = DB::table('users')->where('id', $orderInfo->buyerid)->first();
        $hospitalInfo = DB::table('hospital')->where('id', $orderInfo->hospital)->first();
        $content->title($hospitalInfo->hospital.$ext);
        if(Admin::user()->isRole('warehouse')){
            $fenPi = DB::table('order_fenpi')->where([['order_id', $id],['warehouse_id', $user_id]])->get();
        }else{
            $fenPi = DB::table('order_fenpi')->where('order_id', $id)->get();
        }
        if(empty($fenPi->toArray())){
            admin_warning('提醒：','订单号为：'.$orderInfo->orderid.'中没有您的出库任务');
            return redirect('/admin/orders');
        }
        $gifts = [];
        $data = [];
        $totalprice = 0;
        $getGift = [];//用于计算赠品
        foreach ($fenPi as $key=>$value){
            //订单商品信息
            $medicinalInfo = DB::table('medicinal')->find($value->medicinal_id);
            $producer = DB::table('producer')->find($medicinalInfo->producer_id);
            $line = DB::table('productlines')->find($medicinalInfo->line_id);
            $category = DB::table('categories')->find($medicinalInfo->category_id);
            $orderMedicinals = DB::table('order_medicinals')->where([['order_id', $id],['medicinal_id', $value->medicinal_id]])->first();
            $totalprice += $value->num * $orderMedicinals->price;
            $getGift[] = $value->medicinal_id;
            $data[$key] = [
                //基础信息
                'medicinal_id' => $value->medicinal_id,
                'medicinal' => $medicinalInfo->medicinal,
                'medicinalnum' => $medicinalInfo->medicinalnum,
                'producer' => $producer->name,
                'line' => $line->linename,
                'category' => $category->categoryname,
                'manufacturinglicense' => $medicinalInfo->manufacturinglicense,
                'manufactur' => $medicinalInfo->manufactur,
                'specification' => $medicinalInfo->specification,
                'unit' => $medicinalInfo->unit,
                'registnum' => $medicinalInfo->registnum,
                'registivalidate' => $medicinalInfo->registivalidate,
                'storagecondition'=> $medicinalInfo->storagecondition,
                 //额外信息
                'price'=> $orderMedicinals->price,
                'num' => $value->num,
                'prices' => $value->num * $orderMedicinals->price,
                //补充信息
                'batchnumber' => $value->batchnumber,
                'novirus' => $value->novirus,
                'makedate' => $value->makedate,
                'invalidate' => $value->invalidate,
                'boxformat' => $value->boxformat,
                'originmake' => $value->originmake,
                'tips' => $value->tips,
                'depart' => ''
            ];
        }
        //订单赠品信息
        if(Admin::user()->isRole('warehouse')){
            $orderGift = DB::table('order_gift')->where([['order_id', $id],['warehouse_id', $user_id]])->get();
        }else{
            $orderGift = DB::table('order_gift')->where('order_id', $id)->get();
        }
        if(!empty($orderGift->toArray(true))) {
            foreach ($orderGift->toArray(true) as $key => $item) {
                $giftMedicinal = DB::table('medicinal')->find($item->medicinal_id);
                $giftOrigin = DB::table('medicinal')->find($item->origin_id);
                $gifts[] = [
                    'medicinal' => $giftMedicinal->medicinal,
                    'medicinalnum' => $giftMedicinal->medicinalnum,
                    'num' => $item->num,
                    'origin' => $giftOrigin->medicinal . '[' . $giftOrigin->medicinalnum . ']'
                ];
            }
        }
        $totalcn = $this->get_amount($totalprice);
        if($hospitalInfo->hospital == '中国人民解放军中部战区总医院（汉口院区）'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['hk'][0].$hospitalInfo->hospital,
                $headername['hk'][1].$orderInfo->orderid,
                $headername['hk'][2],
                $headername['hk'][3].$buyerInfo->name,
                $headername['hk'][4],
                $headername['hk'][5].date('Y-m-d',strtotime($orderInfo->created_at)),
                $headername['hk'][6],
                $headername['hk'][7],
                $headername['hk'][8],
                $headername['hk'][9]

            ];
            $content->body(view('admin.prints.hankou',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['hk'],'lists'=>$data, 'gift'=>$gifts, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn,]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉亚洲心脏病医院'){
            $title = $ext1;
            $tabletitle = [$title,
                $headername['xzb'][0],
                $headername['xzb'][1],
                $headername['xzb'][2].$sendcompany,
                $headername['xzb'][3].$hospitalInfo->hospital,
                $headername['xzb'][4],
                $headername['xzb'][5],
                $headername['xzb'][6],
                $headername['xzb'][7]
            ];
            $content->body(view('admin.prints.xinzangbing',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['xzb'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉亚心总医院有限公司'){
            $title = $ext1;
            $tabletitle = [$title,
                $headername['yx'][0],
                $headername['yx'][1],
                $headername['yx'][2].$sendcompany,
                $headername['yx'][3].$hospitalInfo->hospital,
                $headername['yx'][4],
                $headername['yx'][5],
                $headername['yx'][6],
                $headername['yx'][7]
            ];
            $content->body(view('admin.prints.yaxin',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['xzb'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉儿童医院'){
            $title = $hospitalInfo->hospital.$ext1;
            $tabletitle = [$title,
                $headername['et'][0].$sendcompany,
                $headername['et'][1],
                $headername['et'][2],
                $headername['et'][3],
                $headername['et'][4],
                $headername['et'][5],
                $headername['et'][6],
                $headername['et'][7],
                $headername['et'][8],
                $headername['et'][9],
                $headername['et'][10]
            ];
            $content->body(view('admin.prints.ertong',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['et'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '同济医院' || $hospitalInfo->hospital == '同济生活服务部'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['tj'][0].$sendcompany,
                $headername['tj'][1],
                $headername['tj'][2].$hospitalInfo->address,
                $headername['tj'][3].$hospitalInfo->hospital,
                $headername['tj'][4],
                $headername['tj'][5],
                $headername['tj'][6],
                $headername['tj'][7]
            ];
            $content->body(view('admin.prints.tongji',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['tj'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉市第四医院' || $hospitalInfo->hospital == '武汉市第四医院西院'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['ds'][0].$hospitalInfo->hospital,
                $headername['ds'][1],
                $headername['ds'][2],
                $headername['ds'][3],
                $headername['ds'][4],
                $headername['ds'][5],
                $headername['ds'][6]
            ];
            $content->body(view('admin.prints.disi',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['ds'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉大学人民医院'){
            $title = $hospitalInfo->hospital.$ext2;
            $tabletitle = [$title,
                $headername['rm'][0].$sendcompany,
                $headername['rm'][1].date('Y/m/d',strtotime($orderInfo->created_at)),
                $headername['rm'][2],
                $headername['rm'][3]
            ];
            $content->body(view('admin.prints.renmin',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['rm'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '省妇幼'){
            $title = $hospitalInfo->hospital.$ext;
            $tabletitle = [$title,
                $headername['fy'][0].$hospitalInfo->hospital,
                $headername['fy'][1].$orderInfo->orderid,
                $headername['fy'][2],
                $headername['fy'][3].$buyerInfo->name,
                $headername['fy'][4].date('Y/m/d',strtotime($orderInfo->created_at)),
                $headername['fy'][5],
                $headername['fy'][6],
                $headername['fy'][7],
                $headername['fy'][8]
            ];
            $content->body(view('admin.prints.fuyou',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['fy'],'lists'=>$data,'gift'=>$gifts, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉市中心医院'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['zx'][0].$hospitalInfo->hospital,
                $headername['zx'][1].$orderInfo->orderid,
                $headername['zx'][2],
                $headername['zx'][3].$buyerInfo->name,
                $headername['zx'][4],
                $headername['zx'][5].date('Y/m/d',strtotime($orderInfo->created_at)),
                $headername['zx'][6],
                $headername['zx'][7],
                $headername['zx'][8],
                $headername['zx'][9]
            ];
            $content->body(view('admin.prints.zhongxin',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['zx'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else if($hospitalInfo->hospital == '武汉大学中南医院' || $hospitalInfo->hospital == '湖北楚汉精诚医药有限公司武昌分公司'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['zn'][0].$hospitalInfo->hospital,
                $headername['zn'][1].$orderInfo->orderid,
                $headername['zn'][2],
                $headername['zn'][3].$buyerInfo->name,
                $headername['zn'][4],
                $headername['zn'][5].date('Y/m/d',strtotime($orderInfo->created_at)),
                $headername['zn'][6],
                $headername['zn'][7],
                $headername['zn'][8],
                $headername['zn'][9]
            ];
            $content->body(view('admin.prints.zhongnan',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['zn'],'lists'=>$data,'gift'=>$gifts, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }elseif($hospitalInfo->hospital == '湖北省紫阳医业公司'){
            $title = $hospitalInfo->hospital;
            $tabletitle = [$title,
                $headername['zy'][0].$sendcompany,
                $headername['zy'][1].date('Y/m/d',strtotime($orderInfo->created_at)),
                $headername['zy'][2],
                $headername['zy'][3]

            ];
            $content->body(view('admin.prints.ziyang',
                ['orderinfo'=>$orderInfo,'tabletitle'=>$tabletitle,'datatitle'=>$dataname['zy'],'lists'=>$data, 'gift'=>$gifts,'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
            return $content;
        }else{
            admin_toastr('确定有这个医院？','error');
            return redirect('/admin/orders');
        }
    }

    public function finance(Content $content){
        $content->title('财务专用');
        $request = request();
        $id = $request->id;
        $orderInfo = DB::table('orders')->find($id);
        $orderProducts = DB::table('order_medicinals')->where('order_id', $id)->get(['order_id', 'medicinal_id', 'price','num']);
        $medicinals = [];
        foreach ($orderProducts as $key=>$product){
            $medicinal = DB::table('medicinal')->where('id', $product->medicinal_id)->first();
            $medicinals[$key] = [
                'medicinal' => $medicinal->medicinal,
                'medicinalnum'=>$medicinal->medicinalnum,
                'unit'=>$medicinal->unit,
                'num'=>$product->num,
                'price'=>$product->price,
                'prices'=>$product->num*$product->price
            ];
        }
        $content->body(view('admin.prints.finance',[
            'orderinfo'=>$orderInfo, 'medicinals'=>$medicinals
        ])->render());
        return $content;
    }

   
    /*    
    * 数字金额转换成中文大写金额的函数
    * @param String|Int  $num  要转换的小写数字或小写字符串
    *return 大写字母
    *小数位为两位
    **/
    protected function get_amount($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        $num = round($num, 2);
        $num = $num * 100;
        if (strlen($num) > 10) {
            return ['status'=>false,'msg'=>'请检查一下该订单的金额,确定有这么多？'];
        } 
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                $n = substr($num, strlen($num)-1, 1);
            } else {
                $n = $num % 10;
            } 
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            } 
            $i = $i + 1;
            $num = $num / 10;
            $num = (int)$num;
            if ($num == 0) {
                break;
            } 
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            $m = substr($c, $j, 6);
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
            } 
            $j = $j + 3;
        } 
 
        if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
        }
        if (empty($c)) {
            return "零元整";
        }else{
            return $c . "整";
        }
    }
}