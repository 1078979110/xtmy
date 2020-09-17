<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\SetPrice;
use App\Category;
use App\Hospitalprice;
use App\Medicinal;
use App\Order;
use App\Producer;
use App\Productline;
use App\Salelist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ApiController extends AdminController {
    public $errorrow = '';
    public $errornum = 0;
    public $errorsheet = '';
	public function line() {
		$producer_id = $_GET['q'];
		return Category::getLineIdNameById($producer_id);
	}
	public function category() {
		$line_id = $_GET['q'];
		return Category::getCategoryIdNameById($line_id);
	}

	public function medicinalStatus() {
		if (request()->isMethod('post')) {
			$id = $_POST['id'];
			$status = $_POST['status'];
			if ($status == 1) {
				$nowstatus = 0;
			} else {
				$nowstatus = 1;
			}
			$result = Medicinal::where('id', $id)->update(['status' => $nowstatus]);
			if ($result) {
				return ['status' => true, 'title' => '操作', 'msg' => '操作成功'];
			} else {
				return ['status' => false, 'title' => '操作', 'msg' => '失败，请稍后再试'];
			}
		}
	}

	public function getMedicinals(Request $request) {
		$medicinal = $request->get('q');
		$result = Medicinal::orWhere('medicinal', 'like', '%' . $medicinal . '%')->orWhere('medicinalnum', 'like', '%' . $medicinal . '%')->orWhere('batchnumber', 'like', '%' . $medicinal . '%')->paginate(null, ['id', 'medicinal', 'medicinalnum', 'medicinalnum as text']);
		foreach ($result->items() as $val) {
			$val->text = '药品名称：' . $val->medicinal . ' 产品货号：' . $val->medicinalnum;
		}
		return $result;
	}

	public function changeOrderStatus() {
		if (request()->isMethod('post')) {
			$id = $_POST['id'];
			$curr_status = Order::where('id', $id)->value('orderstatus');
			if($curr_status == 1 || $curr_status ==2){
			    $curr_status = 3;
            }else if($curr_status ==3 ){
			    if(Admin::user()->isRole('wholesale'))
			    $curr_status = 4;
            }else if($curr_status ==4){
                if(Admin::user()->isRole('finance'))
                $curr_status = 5;
            }else if($curr_status ==5){
                if(Admin::user()->isRole('warehouse'))
                $curr_status = 6;
            }else if($curr_status ==6){
                if(Admin::user()->isRole('warehouse'))
                    $curr_status = 7;
            }
			$result = Order::where('id', $id)->update(['orderstatus' => $curr_status]);
			if ($result >=0) {
				return ['status' => true, 'title' => '订单', 'msg' => '操作成功'];
			} else {
				return ['status' => false, 'title' => '订单', 'msg' => '失败，请稍后再试'];
			}
		}
	}

	public function changeOrderInfoPrice() {
		if (request()->isMethod('post')) {
			$data = $_POST['info'];
			$id = $_POST['id'];
			$totalprice = 0;
			foreach ($data as $key => $val) {
				$totalprice += $val['num'] * $val['price'];
			}
			$result = Order::where('id', $id)->update(['totalprice' => $totalprice, 'orderinfo' => json_encode($data)]);
			if ($result) {
				admin_toastr('修改价格成功', 'success');
				return redirect('/admin/orders');
			} else {
				admin_toastr('修改价格失败，请稍后重试', 'error');
				return redirect('/admin/orders');
			}
		}
	}

	public function orderGift() {
		if (request()->isMethod('post')) {
			$id = request()->post('id');
			$data = request()->post('gift');
			$gift = [];
			if(!empty($data)){
                foreach ($data as $key => $val) {
                    if ($val['num'] > 0) {
                        $gift[] = ['id' => $val['id'], 'medicinal' => $val['medicinal'], 'specification' => $val['specification'], 'num' => $val['num']];
                    }
                }
                $result = Order::where('id', $id)->update(['gift' => json_encode($gift)]);
                if ($result) {
                    admin_toastr('赠品设置成功！', 'success');
                    return redirect('/admin/orders');
                } else {
                    admin_toastr('设置失败，请稍后重试', 'error');
                    return redirect('/admin/orders');
                }
            }
		}
	}

	public function changeInfo() {
		if (request()->isMethod('post')) {
			$data = $_POST;
			$telephone = Admin::user()->username;
			$infos = Salelist::where('telephone', $telephone)->get()->toArray(true);
			$info = $infos[0];
			$arr = [];
			if ($data['name'] != $info['name']) {
				$arr['name'] = $data['name'];
			}
			if ($data['password'] != '') {
				$arr['password'] = bcrypt($data['password']);
				DB::table('admin_users')->where('id', Admin::user()->id)->update($arr);
			}
			if (isset($data['address'])) {
				$arr['address'] = $data['address'];
			}
			$result = Salelist::where('telephone', $telephone)->update($arr);
			if ($result) {
				admin_toastr('操作成功', 'success');
				return redirect('/admin/setting/info');
			} else {
				admin_toastr('操作失败', 'error');
				return redirect('/admin/setting/info');
			}
		}
	}

	public function medicinals() {
		if (request()->isMethod('post')) {
		    $this->errorrow = '';
		    $this->errornum = 0;
		    //$producer_id = request()->post('producer_id');
            //$line_id = request()->post('line_id');
            //$category_id = request()->post('category_id');
			$excel = request()->file('excel');
			$ext_arr = ['xls', 'xlsx'];
			$re = $this->uploadFile($excel, $ext_arr);
			if ($re['status'] == 'error') {
				admin_toastr($re['msg'], $re['status']);
				return redirect('/admin/excel/medicinals');
			} else {
				try {
					$filename = $re['info'];
					$real_file = str_replace('\\','/','storage/' . $filename);
					if (!is_file($real_file)) {
						admin_toastr('文件不存在！', 'error');
						return redirect('/admin/excel/medicinals');
					}

					Excel::load($real_file, function ($reader) {
						$data = $reader->get()->toArray(true);
						$value = [];
						foreach ($data as $k=>$v) {
                            foreach ($v as $key => $val) {
                                if (!$val['产品货号']) {
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($k+1).'表第'.($key+2).'行产品货号为空，';
                                    continue;
                                }

                                if (!$val['器械名称']) {
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($k+1).'表第'.($key+2).'行器械名称为空，';
                                    continue;
                                }

                                if(!$val['厂家']){
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($k+1).'表第'.($key+2).'行厂家为空，';
                                    continue;
                                }
                                $producer_id = Producer::where('name',$val['厂家'])->value('id');
                                if(!$producer_id){
                                    $producer_id = DB::table('producer')->insertGetId(['name'=>$val['厂家'],'productionlicense'=>$val['许可证号'], 'productionaddress'=> $val['生产厂商'], 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
                                }
                                if(!$val['产品线']){
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($k+1).'表第'.($key+2).'行产品线为空，';
                                    continue;
                                }
                                $line_id = Productline::where([['linename', $val['产品线']],['producer_id', $producer_id]])->value('id');
                                if(!$line_id){
                                    $line_id = DB::table('productlines')->insertGetId(['linename'=>$val['产品线'],'producer_id'=>$producer_id, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
                                }
                                if(!$val['产品分类']){
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($k+1).'表第'.($key+2).'行产品分类为空，';
                                    continue;
                                }
                                $category_id = Category::where([['categoryname',$val['产品分类']],['line_id', $line_id],['producer_id',$producer_id]])->value('id');
                                if(!$category_id){
                                    $category_id = DB::table('categories')->insertGetId(['categoryname'=>$val['产品分类'],'line_id'=> $line_id,'producer_id'=>$producer_id, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
                                }
                                /*$has_insert = DB::table('medicinal')->where([['medicinalnum', $val['产品货号']],['producer_id', $producer_id],['line_id',$line_id],['category_id',$category_id]])->exists();
                                if ($has_insert) {
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($k+1).'表第'.($key+2).'行已存在，';
                                    continue;
                                }*/
                                $makedate = is_object($val['生产日期']) ? $val['生产日期']->format('Y-m-d H:i:s') : $val['生产日期'];
                                $invalidate = is_object($val['失效日期']) ? $val['失效日期']->format('Y-m-d H:i:s') : $val['失效日期'];
                                $registivalidate = is_object($val['注册证失效日期']) ? $val['注册证失效日期']->format('Y-m-d H:i:s') : $val['注册证失效日期'];
                                $value['medicinal'] = $val['器械名称'];
                                $value['medicinalnum'] = $val['产品货号'];
                                $value['manufacturinglicense'] = isset($val['许可证号']) ? $val['许可证号'] : '';
                                $value['manufactur'] = isset($val['生产厂商']) ? $val['生产厂商'] : '';
                                $value['producer_id'] = $producer_id;
                                $value['line_id'] = $line_id;
                                $value['category_id'] = $category_id;
                                $value['specification'] = isset($val['规格型号']) ? $val['规格型号'] : '';
                                $value['unit'] = isset($val['单位'])?$val['单位']:'';
                                $value['batchnumber'] = isset($val['批号']) ? $val['批号'] : '';
                                $value['makedate'] = $makedate;
                                $value['invalidate'] = $invalidate;
                                $value['registnum'] = isset($val['注册证号']) ? $val['注册证号'] : '';
                                $value['registivalidate'] = $registivalidate;
                                $value['storagecondition'] = isset($val['储存条件']) ? $val['储存条件'] : '';
                                $value['status'] = isset($val['status']) ? $val['status'] : 0;
                                DB::table('medicinal')->insert($value);
                            }
                        }
					});
					if($this->errornum == 0){
                        admin_toastr('导入成功', 'success');
                    }else{
                        admin_warning('共'.$this->errornum.'未导入','结果为：'.$this->errorrow);
                    }

					return redirect('/admin/medicinals');
				} catch (\Exception $e) {
					return $e->getMessage();
				}
			}
		}
	}

	public function setPrice() {
		if (request()->isMethod('post')) {
		    $this->errornum = 0;
		    $this->errorrow = '';
			$excel = request()->file('excel');
			$ext_arr = ['xls', 'xlsx'];
			$re = $this->uploadFile($excel, $ext_arr);
			$hospital = request()->post('hospitalid');
			if ($re['status'] == 'error') {
				admin_toastr($re['msg'], $re['status']);
				return redirect('/admin/excel/setprice');
			} else {
				$filename = $re['info'];
                $real_file = str_replace('\\','/','storage/' . $filename);
				if (!is_file($real_file)) {
					admin_toastr('文件不存在！', 'error');
					return redirect('/admin/excel/setprice');
				}
				try {
					Excel::load($real_file, function ($reader) use ( $hospital) {
						$data = $reader->get()->toArray(true);
						$value = [];
						foreach($data as $k=>$v){
                            foreach ($v as $key => $val) {
                                if (!isset($val['产品货号']) || empty($val['产品货号']) || !isset($val['价格']) || empty($val['价格'])) {
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($key+2).'行,';
                                    continue;
                                }
                                $medicinalid = Medicinal::where('medicinalnum', $val['产品货号'])->value('id');
                                if(!$medicinalid){//过滤产品库不存在的货号产品
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($key+2).'行产品不存在,';
                                    continue;
                                }
                                $has = Hospitalprice::where([['medicinalid', $medicinalid],['hospitalid', $hospital]])->exists();//过滤重复的价格设置
                                if($has){
                                    $this->errornum ++;
                                    $this->errorrow .= '第'.($key+2).'行已存在,';
                                    continue;
                                }
                                $value['hospitalid'] = $hospital;
                                $value['medicinalid'] = $medicinalid;
                                $value['medicinalnum'] = $val['产品货号'];
                                $value['price'] = $val['价格'];
                                DB::table('hospitalprice')->insert($value);
                            }
                        }
					});
					if($this->errornum == 0) {
                        admin_toastr('导入成功', 'success');
                    }else{
                        admin_warning('共'.$this->errornum.'未导入','结果为：'.$this->errorrow);
                    }
					return redirect('/admin/excel/setprice');
				} catch (\Exception $e) {
					return $e->getMessage();
				}
			}
		}
	}

	/**
	 *
	 * @param $file 文件对象
	 * @param array $option 后缀名，数组
	 */
	protected function uploadFile($file, $option, $disk = 'admin') {
		$fileExtension = $file->getClientOriginalExtension();
		if (!in_array($fileExtension, $option)) {
			return array('status' => 'error', 'msg' => '文件类型不正确');
		}
		$tmpFile = $file->getRealPath();
		if (!is_uploaded_file($tmpFile)) {
			return array('status' => 'error', 'msg' => '非法上传途径');
		}
		$fileName = date('Y_m_d') . '/' . md5(time()) . mt_rand(0, 9999) . '.' . $fileExtension;
		if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile))) {
			return array('status' => 'success', 'msg' => '上传成功', 'info' => $fileName);
		}
	}

	public function gifts(Content $content, Request $request) {
		$content->title('赠品设置');
		$id = $request->get('id');
		$content->body(view('admin.order.selectgift', ['id' => $id])->render());
		return $content;
	}

	public function searchmedicinal(Request $request) {
		$q = $request->get('q');
		$m = Medicinal::where('medicinal', 'like', '%' . $q . '%')->orWhere('specification', $q)->get(['id', 'medicinal', 'specification', 'stock'])->toArray();
		return $m;
	}
    public function updateAttr(Request $request){
        $data = $_POST;
        $orderinfo = Order::where('id', $data['id'])->value('orderinfo');
        $infos = json_decode($orderinfo, true);
        foreach ($infos as $key => $info){
            $infos[$key]['batchnumber'] = $data['info'][$info['id']]['batchnumber'];
            $infos[$key]['makedate'] = $data['info'][$info['id']]['makedate'];
            $infos[$key]['invalidate'] = $data['info'][$info['id']]['invalidate'];
            $infos[$key]['boxformat'] = $data['info'][$info['id']]['boxformat'];
            $infos[$key]['novirus'] = $data['info'][$info['id']]['novirus'];
            $infos[$key]['originmake'] = $data['info'][$info['id']]['originmake'];
            $infos[$key]['tips'] = $data['info'][$info['id']]['tips'];
        }
        Order::where('id', $data['id'])->update(['orderinfo'=>json_encode($infos)]);
        admin_toastr('操作成功', 'success');
        return redirect('/admin/orders');
    }
    

    public function orders(Request $request){
        if (request()->isMethod('post')) {
            $this->errornum = 0;
            $this->errorrow = '';
            $ext_arr = ['xls', 'xlsx'];
            $re = $this->uploadFile($request->file('file'), $ext_arr);
            if ($re['status'] == 'error') {
                admin_toastr($re['msg'], $re['status']);
                return redirect('/admin/orders');
            } else {
                $filename = $re['info'];
                $real_file = str_replace('\\','/','storage/' . $filename);
                if (!is_file($real_file)) {
                    admin_toastr('文件不存在！', 'error');
                    return redirect('/admin/orders');
                }
                try{
                    Excel::load($real_file, function ($reader){
                        $insertdata = $reader->all()->toArray(true);
                        foreach ($insertdata as $key=>$val){
                            $data = [
                                //'orderid' => date('Ymd', time()) . rand(1000, 9999),
                                'ordermonth' => date('Ym', time()),
                                'buyertype' => 1,
                                'orderstatus' => 3,
                                'totalprice' => 0,
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'updated_at' => date('Y-m-d H:i:s', time())
                            ];
                            $info = [];
                            foreach ($val as $k=>$v){
                                if($k == 0){
                                    $data['buyerid'] = Salelist::where('telephone', $v['经销商'])->value('id');
                                    if(!$data['buyerid']){
                                        $this->errornum++;
                                        $this->errorsheet .= $v['经销商'].'表：经销商不存在';
                                        break 2;
                                    }else{
                                        $data['orderid'] = date('YmdHis', time()).$data['buyerid']. rand(100, 999);
                                        $this->errorsheet .= $v['经销商'].'表：';
                                    }
                                }
                                if(!$v['产品货号']){
                                    $this->errornum++;
                                    $this->errorsheet .= '第'.($k+2).'行产品货号不存在';
                                    break 2;
                                }
                                if(!$v['数量']){
                                    $this->errornum++;
                                    $this->errorsheet .= '第'.($k+2).'行数量不存在';
                                    break 2;
                                }
                                if(!$v['价格']){
                                    $this->errornum++;
                                    $this->errorsheet .= '第'.($k+2).'行价格不存在';
                                    break 2;
                                }
                                $medicinalinfo = Medicinal::where('medicinalnum', $v['产品货号'])->first();
                                if(!$medicinalinfo){
                                    $this->errornum++;
                                    $this->errorsheet .= '第'.($k+2).'行产品货号产品不存在';
                                    break 2;
                                }

                                $info[] = [
                                    'id' => $medicinalinfo->id,
                                    'medicinal' => $medicinalinfo->medicinal,
                                    'medicinalnum' => $v['产品货号'],
                                    'price' => round($v['价格'],2),
                                    'unit' => $medicinalinfo->unit,
                                    'num' => $v['数量']
                                ];
                                $data['totalprice'] += round($v['价格'],2)*$v['数量'];
                                $data['orderinfo'] = json_encode($info);
                            }
                            DB::table('orders')->insert($data);
                        }
                    });
                    if($this->errornum == 0){
                        admin_toastr('导入成功','success');
                    }else{
                        admin_warning('未导入订单',$this->errorsheet);
                    }
                    return redirect('/admin/orders');
                }catch (\Exception $e){
                    $msg = $e->getMessage();
                    admin_error($msg);
                    return redirect('/admin/orders');
                }
            }
        }
    }

    public function diaoDu(Request $request){
        $id = $request->id;
        $diaodu = $request->diaodu;
        try{
            foreach ($diaodu as $key => $item){
                if(is_numeric($key)){
                    $_d = [
                        'orderid'=>$id,
                        'medicinalid' => $item['medicinalid'],
                        'num' => $item['num'],
                        'warehouseid' => $item['warehouseid'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time())
                    ];
                    DB::table('orders_diaodu')->where('id',$key)->update($_d);
                }else{
                    $_d = [
                        'orderid'=>$id,
                        'medicinalid' => $item['medicinalid'],
                        'num' => $item['num'],
                        'warehouseid' => $item['warehouseid'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time())
                    ];
                    DB::table('orders_diaodu')->insert($_d);
                }
            }
            admin_toastr('操作成功','success');
            return redirect('/admin/orders');
        }catch (\Exception $e){
            $msg = $e->getMessage();
            admin_error($msg);
            return redirect('/admin/orders');
        }

    }
}