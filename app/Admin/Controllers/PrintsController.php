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
use App\Prints;
class PrintsController extends AdminController{
    public function jxsPrint(Content $content){
        $orderid = request()->get('id');
        $jxsid = Order::where('id',$orderid)->value('buyerid');
        $jxsinfo = Salelist::find($jxsid)->toArray(true);//经销商信息
        $orderinfo = Order::find($orderid)->toArray(true);
        $siteinfo = Site::find(1);
        $ext = '商品销售单';
        $tabletitle = [];
        $datatitle = [];
        $tabletitle[0] = ['购货单位：','编号：','日期：','合计：','发货员：'];
        $tabletitle[1] = ['购货单位：','编号：','部门：','日期：','合计：','发货员：','复核员：','客户签收：'];
        $tabletitle[2] = ['购货单位：','编号：','部门：','日期：','合计：','发货员：','复核员：','客户签收：'];
        $tabletitle[3] = ['购货单位：','单据号：','地址：','日期：','合计(大写)：','总金额：','本页小计：','发货员：','复核员：','储运条件：常温库','签收：','发货地址：','联系电话：','非质量问题概不退换'];
        $tabletitle[4] = ['购货单位：','单据号：','收货地址：', '日期：', '合计（大写）：','本页小计：', '发货员：', '复核员：', '储运条件：常温库', '签收：','发货地址：','联系电话：','非质量问题概不退换'];
        $datatitle[0] = ['序号','产品名称','规格型号','数量','单位','批号','失效日期','注册证号'];
        $datatitle[1] = ['器械名称','规格型号','单位','数量','单价','批号','有效期至'];
        $datatitle[2] = ['器械名称','规格型号','单位','数量','批号','有效期至'];
        $datatitle[3] = ['器械名称','生产商','规格型号','单位','数量','单价','金额','批号','注册证号','有效期至'];
        $datatitle[4] = ['器械名称', '生产厂商', '生产企业许可证', '规格型号', '单位','数量', '批号', '注册证号', '生产日期', '失效日期'];
        $dataname = [];//表格各项信息
        $totalprice = 0;
        $data = [];
        $infos = json_decode($orderinfo['orderinfo'], true);
        foreach ($infos as $key=>$val){
            $medicinal = Medicinal::find($val['id']);
            $totalprice +=  $val['num']*$val['price'];
            $t_ = [
                'medicinal' => $medicinal['medicinal'],
                'specification' => $medicinal['specification'],
                'unit' => $medicinal['unit'],
                'num' => $val['num'],
                'manufacturinglicense' => $medicinal['manufacturinglicense'],
                'batchnumber' => isset($val['batchnumber'])?$val['batchnumber']:$medicinal['batchnumber'],
                'invalidate' => isset($val['invalidate'])?$val['invalidate']:'',
                'registnum' => $medicinal['registnum'],
                'manufactur' => $medicinal['manufactur'],
                'makedate' => isset($val['makedate'])?$val['makedate']:'',
                'price' => $val['price'],
                'prices' => $val['num']*$val['price']
            ];
            $data[] = $t_;
        }
        $dataname[0] = [
            $tabletitle[0][0].$jxsinfo['name'],
            $tabletitle[0][1].$orderinfo['orderid'],
            $tabletitle[0][2].date('Y/m/d', time()),
            $tabletitle[0][3].$totalprice.'元',
            $tabletitle[0][4]
        ];
        $dataname[1] = [
            $tabletitle[1][0].$jxsinfo['name'],
            $tabletitle[1][1].$orderinfo['orderid'],
            $tabletitle[1][2].$jxsinfo['depart'],
            $tabletitle[1][3].date('Y/m/d', time()),
            $tabletitle[1][4].$totalprice.'元',
            $tabletitle[1][5],
            $tabletitle[1][6],
            $tabletitle[1][7]
        ];
        $dataname[2] = $dataname[1];
        $dataname[3] = [
            $tabletitle[3][0].$jxsinfo['name'],
            $tabletitle[3][1].$orderinfo['orderid'],
            $tabletitle[3][2].$jxsinfo['address'],
            $tabletitle[3][3].date('Y/m/d', time()),
            $tabletitle[3][4].$this->get_amount($totalprice),
            $tabletitle[3][5].$totalprice.'元',
            $tabletitle[3][6],
            $tabletitle[3][7],
            $tabletitle[3][8],
            $tabletitle[3][9],
            $tabletitle[3][10],
            $tabletitle[3][11].$siteinfo['address'],
            $tabletitle[3][12].$siteinfo['telephone'],
            $tabletitle[3][13]
        ];
        $dataname[4] = [
            $tabletitle[4][0].$jxsinfo['name'],
            $tabletitle[4][1].$orderinfo['orderid'],
            $tabletitle[4][2].$jxsinfo['address'],
            $tabletitle[4][3].date('Y/m/d', time()),
            $tabletitle[4][4].$this->get_amount($totalprice),
            $tabletitle[4][5],
            $tabletitle[4][6],
            $tabletitle[4][7],
            $tabletitle[4][8],
            $tabletitle[4][9],
            $tabletitle[4][10].$siteinfo['siteaddress'],
            $tabletitle[4][11].$siteinfo['telephone'],
            $tabletitle[4][12]

        ];
        $totalcn = $this->get_amount($totalprice);
        if(isset($totalcn['status'])){
            admin_toastr($totalcn['msg'],'error');
            return redirect('/admin/orders');
        }
        $content->title($jxsinfo['name'].$ext);
        $id = request()->get('id');
        $content->body(view('admin.prints.jxs',
            ['title'=>$siteinfo['sitename'].$ext,'tabletitle'=>$dataname,'datatitle'=>$datatitle,'lists'=>$data, 'jsondata'=>json_encode($data) ,'total'=>$totalprice, 'totalcn'=>$totalcn]
            )->render());
        return $content;
    }
    
    public function hospitalPrint(Content $content){
        $ext = '商品销售单';
        $ext1 = '送货单';
        $ext2 = '医用耗材采购单';
        $sendcompany = "武汉协同贸易有限公司";
        $headername = [];//出货单基础信息名
        $dataname = [];//订单货物标题
        $headername['hk'] = ['购货单位：','编号：','部门：','业务员：','开票员：','日期：','合计：','发货员：','复核员：','客户签字'];
        $headername['xzb'] =['采购订单号：','订单日期：','供应商：','收货单位：','送货人：','送货日期：','收货人：','收货日期：'];
        $headername['yx'] =['采购订单号：','订单日期：','供应商：','收货单位：','送货人：','送货日期：','收货人：','收货日期：'];
        $headername['et'] = ['供应单位：','采购人：','采购类型：','仓库：','订单号：','订单日期：','送货单号：','合计：','发货员：','复核员：','客户签收：']; 
        $headername['tj'] = ['商业公司全称：','送货单号：','单位地址电话：','收货单位：','科室','送货日期：','合计：','收货单位及经手人签字：'];
        $headername['ds'] = ['购货单位：','开票日期：','发票号：','合计：','送货人：','收货人：','联系方式：'];
        $headername['rm'] = ['经销商：','采购日期：', '科室：','合计：'];
        $headername['fy'] = ['购货单位：','编号：','部门：', '业务员：','日期：','合计：','发货员：','复核员：','客户签收'];
        $headername['zx'] = ['购货单位：','编号：','部门：呼吸及危重症二病区（后湖）','业务员：','开票员：','日期：','合计：','发货员：','复核员：','客户签收'];
        $headername['zn'] = ['购货单位：','编号：','部门：','业务员：','开票员：','日期：','合计：','发货员：','复核员：','客户签收'];
        $headername['zy'] = ['经销商：','采购日期：','科室：湖北省紫阳医业公司','合计：'];
        
        $dataname['hk'] = ['器械名称','规格型号','单位','数量','单价','金额','生产日期','批号','注册证书','有效期至'];
        $dataname['xzb'] = ['序号','产品编码','产品名称','规格型号','装箱规格','生产厂家','数量','单位','单价(元)','产品批号','产品有效期','医疗器械注册证书','医疗器械注册证有效期','备注'];
        $dataname['yx'] = ['序号','产品编码','产品名称','规格型号','装箱规格','生产厂家','数量','单位','单价(元)','产品批号','产品有效期','医疗器械注册证书','医疗器械注册证有效期'];
        $dataname['et'] = ['材料名称','规格型号','单位','生产批号','有效日期','单价(元)','数量','总价(元)','生产厂家','灭菌日期'];
        $dataname['tj'] = ['产品编号','品名','规格','单位','数量','单价','金额','生产批号','有效期','备注','产地'];
        $dataname['ds'] = ['药品编号','商品名称','生产厂家','型号规格','单位','数量','单价','金额','生产批号','灭菌批号','有效期至','科室'];
        $dataname['rm'] = ['产品名称','规格型号','数量','单位','单价','金额','生产批号','灭菌批号','注册证号','产品有效期','生产厂家','原产地'];
        $dataname['fy'] = ['器械名称','规格型号','单位','数量','单价','金额','生产日期','批号','注册证号','有效期至'];
        $dataname['zx'] = ['器械名称','生产商','规格型号','单位','数量','单价','金额','生产日期','批号','注册证号','有效期至'];
        $dataname['zn'] = ['器械名称','规格型号','单位','数量','单价','金额','生产日期','批号','注册证号','有效期至'];
        $dataname['zy'] = ['产品名称','规格型号','单位','数量','包装规格','单价','金额','产品批号','灭菌批号','生产日期','产品有效期','产品注册证','注册证有效期','生产厂家'];
        $orderid = $_GET['id'];
        $orderAllInfo = Order::find($orderid)->toArray(true);
        $hospitalinfo = Hospital::find($orderAllInfo['hospital']);
        $content->title($hospitalinfo['hospital'].$ext);
        $title = '';
        $orderinfo = json_decode($orderAllInfo['orderinfo'], true);
        $buyername = Salelist::where('id',$orderAllInfo['buyerid'])->value('name');
        $tabletitle = [];
        $data = [];
        $totalprice = 0;
        foreach ($orderinfo as $key=>$val){
            $medicinal = Medicinal::find($val['id']);

            //$price = Hospitalprice::where([['hospitalid',$orderAllInfo['hospital']],['medicinalid',$val['id']]])->value('price');
            $totalprice +=  $val['num']*$val['price'];
            $registivalidate = date('Y-m-d', strtotime($medicinal['registivalidate']));
            $data[] = array(
                //药品基础信息
                'medicinal' => $medicinal['medicinal'],
                'medicinalnum' => $medicinal['medicinalnum'],
                'manufacturinglicense' => $medicinal['manufacturinglicense'],
                'manufactur' => $medicinal['manufactur'],
                'specification' => $medicinal['specification'],
                'unit' => $medicinal['unit'],
                'batchnumber' => isset($val['batchnumber'])?$val['batchnumber']:'',
                'makedate' => isset($val['makedate'])?$val['makedate']:'',
                'registnum' => $medicinal['registnum'],
                'registivalidate' => empty($registivalidate)?$medicinal['registivalidate']:$registivalidate,
                'invalidate' => isset($val['invalidate'])?$val['invalidate']:'',
                'storagecondition' => $medicinal['storagecondition'],
                //订单信息
                'price' => $val['price'],
                'prices' => $val['num']*$val['price'],
                'num' => $val['num'],
                'gift' => $orderAllInfo['gift'],
                //额外信息
                'boxformat' =>isset($val['boxformat'])?$val['boxformat']:'',//装箱规格
                'novirus' =>isset($val['novirus'])?$val['novirus']:'',//灭菌批号
                'depart' => '',//部门
            );
        }
        $totalcn = $this->get_amount($totalprice);
        if(isset($totalcn['status'])){
            admin_toastr($totalcn['msg'],'error');
            return redirect('/admin/orders');
        }
        if($hospitalinfo['hospital'] == '中国人民解放军中部战区总医院（汉口院区）'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['hk'][0].$hospitalinfo['hospital'],
                $headername['hk'][1].$orderAllInfo['orderid'],
                $headername['hk'][2],
                $headername['hk'][3],
                $headername['hk'][4],
                $headername['hk'][5].date('Y-m-d',strtotime($orderAllInfo['created_at'])),
                $headername['hk'][6],
                $headername['hk'][7],
                $headername['hk'][8],
                $headername['hk'][9]
                
            ];
            $content->body(view('admin.prints.hankou',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['hk'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉亚洲心脏病医院'){
            $title = $ext1;
            $tabletitle = [$title, 
                $headername['xzb'][0].$orderAllInfo['orderid'],
                $headername['xzb'][1].date('Y-m-d',strtotime($orderAllInfo['created_at'])),
                $sendcompany,
                $hospitalinfo['hospital'],
                $headername['xzb'][4],
                $headername['xzb'][5],
                $headername['xzb'][6],
                $headername['xzb'][7]
            ];
            $content->body(view('admin.prints.xinzangbing', 
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['xzb'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉亚心总医院有限公司'){
            $title = $ext1;
            $tabletitle = [$title,
                $headername['xzb'][0].$orderAllInfo['orderid'],
                $headername['xzb'][1].date('Y-m-d',strtotime($orderAllInfo['created_at'])),
                $sendcompany,
                $hospitalinfo['hospital'],
                $headername['xzb'][4],
                $headername['xzb'][5],
                $headername['xzb'][6],
                $headername['xzb'][7]
            ];
            $content->body(view('admin.prints.yaxin',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['xzb'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉儿童医院'){
            $title = $hospitalinfo['hospital'].$ext1;
            $tabletitle = [$title,
                $headername['et'][0].$sendcompany,
                $headername['et'][1].$hospitalinfo['contactman'],
                $headername['et'][2],
                $headername['et'][3],
                $headername['et'][4].$orderAllInfo['orderid'],
                $headername['et'][5].date('Y/m/d', strtotime($orderAllInfo['created_at'])),
                $headername['et'][6],
                $headername['et'][7],
                $headername['et'][8],
                $headername['et'][9],
                $headername['et'][10]
            ];
            $content->body(view('admin.prints.ertong',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['et'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '同济医院' || $hospitalinfo['hospital'] == '同济生活服务部'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['tj'][0].$sendcompany,
                $headername['tj'][1],
                $headername['tj'][2].$hospitalinfo['address'],
                $headername['tj'][3].$hospitalinfo['hospital'],
                $headername['tj'][4],
                $headername['tj'][5],
                $headername['tj'][6],
                $headername['tj'][7]
            ];
            $content->body(view('admin.prints.tongji',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['tj'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉市第四医院' || $hospitalinfo['hospital'] == '武汉市第四医院西院'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['ds'][0].$hospitalinfo['hospital'],
                $headername['ds'][1],
                $headername['ds'][2],
                $headername['ds'][3],
                $headername['ds'][4],
                $headername['ds'][5],
                $headername['ds'][6]
            ];
            $content->body(view('admin.prints.disi',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['ds'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉大学人民医院'){
            $title = $hospitalinfo['hospital'].$ext2;
            $tabletitle = [$title,
                $headername['rm'][0].$hospitalinfo['hospital'],
                $headername['rm'][1].date('Y/m/d',strtotime($orderAllInfo['created_at'])),
                $headername['rm'][2],
                $headername['rm'][3]
            ];
            $content->body(view('admin.prints.renmin',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['rm'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '省妇幼'){
            $title = $hospitalinfo['hospital'].$ext;
            $tabletitle = [$title,
                $headername['fy'][0].$sendcompany,
                $headername['fy'][1].$orderAllInfo['orderid'],
                $headername['fy'][2],
                $headername['fy'][3].$buyername,
                $headername['fy'][4].date('Y/m/d',strtotime($orderAllInfo['created_at'])),
                $headername['fy'][5],
                $headername['fy'][6],
                $headername['fy'][7],
                $headername['fy'][8]
            ];
            $content->body(view('admin.prints.fuyou',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['fy'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉市中心医院'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['zx'][0].$hospitalinfo['hospital'],
                $headername['zx'][1].$orderAllInfo['orderid'],
                $headername['zx'][2],
                $headername['zx'][3].$buyername,
                $headername['zx'][4],
                $headername['zx'][5].date('Y/m/d',strtotime($orderAllInfo['created_at'])),
                $headername['zx'][6],
                $headername['zx'][7],
                $headername['zx'][8],
                $headername['zx'][9]
            ];
            $content->body(view('admin.prints.zhongxin',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['zx'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else if($hospitalinfo['hospital'] == '武汉大学中南医院' || $hospitalinfo['hospital'] == '湖北楚汉精诚医药有限公司武昌分公司'){
            $title = $sendcompany.$ext;
            $tabletitle = [$title,
                $headername['zn'][0].$hospitalinfo['hospital'],
                $headername['zn'][1].$orderAllInfo['orderid'],
                $headername['zn'][2],
                $headername['zn'][3].$buyername,
                $headername['zn'][4],
                $headername['zn'][5].date('Y/m/d',strtotime($orderAllInfo['created_at'])),
                $headername['zn'][6],
                $headername['zn'][7],
                $headername['zn'][8],
                $headername['zn'][9]
            ];
            $content->body(view('admin.prints.zhongnan',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['zn'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }elseif($hospitalinfo['hospital'] == '湖北省紫阳医业公司'){
            $title = $hospitalinfo['hospital'];
            $tabletitle = [$title,
                $headername['zy'][0].$sendcompany,
                $headername['zy'][1].date('Y/m/d',strtotime($orderAllInfo['created_at'])),
                $headername['zy'][2],
                $headername['zy'][3]
                
            ];
            $content->body(view('admin.prints.ziyang',
                ['tabletitle'=>$tabletitle,'datatitle'=>$dataname['zy'],'lists'=>$data, 'jsondata'=>json_encode($data), 'total'=>$totalprice, 'totalcn'=>$totalcn]
                )->render());
            return $content;
        }else{
          admin_toastr('确定有这个医院？','error');
          return redirect('/admin/orders');
        }
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