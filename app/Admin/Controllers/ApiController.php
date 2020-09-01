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
		$result = Medicinal::orWhere('medicinal', 'like', '%' . $medicinal . '%')->orWhere('medicinalnum', 'like', '%' . $medicinal . '%')->orWhere('batchnumber', 'like', '%' . $medicinal . '%')->paginate(null, ['id', 'medicinal', 'specification', 'medicinalnum as text']);
		foreach ($result->items() as $val) {
			$val->text = '药品名称：' . $val->medicinal . ' 规格：' . $val->specification;
		}
		return $result;
	}

	public function changeOrderStatus() {
		if (request()->isMethod('post')) {
			$id = $_POST['id'];
			$curr_status = Order::where('id', $id)->value('orderstatus');
			if($curr_status == 1 || $curr_status ==2){
			    $curr_status = 3;
            }else{
			    $curr_status++;
            }
			$result = Order::where('id', $id)->update(['orderstatus' => $curr_status]);
			if ($result) {
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
		    $producer_id = request()->post('producer_id');
            $line_id = request()->post('line_id');
            $category_id = request()->post('category_id');
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

					Excel::load($real_file, function ($reader) use ($producer_id, $line_id, $category_id) {
						$data = $reader->all()->toArray(true);
						$value = [];
						foreach ($data as $k=>$v) {
                            foreach ($v as $key => $val) {
                                if (!isset($val['器械名称']) || empty($val['器械名称'])) {
                                    admin_toastr('excel表数据结构与模板不相符，请修改后再进行导入！', 'error');
                                    return redirect('/admin/excel/medicinals');
                                }
                                /*$has_insert = Medicinal::where('specification', $val['规格型号'])->first();
                                if (!empty($has_insert)) {
                                    break;
                                }*/
                                $makedate = (array)$val['生产日期'] ? (array)$val['生产日期'] : '';
                                $invalidate = (array)$val['失效日期'] ? (array)$val['失效日期'] : '';
                                $registivalidate = (array)$val['注册证失效日期'] ? (array)$val['注册证失效日期'] : '';
                                $value['medicinal'] = $val['器械名称'];
                                $value['manufacturinglicense'] = $val['许可证号'] ? $val['许可证号'] : '';
                                $value['manufactur'] = $val['生产厂商'] ? $val['生产厂商'] : '';
                                $value['producer_id'] = $producer_id;
                                $value['line_id'] = $line_id;
                                $value['category_id'] = $category_id;
                                $value['specification'] = $val['规格型号'] ? $val['规格型号'] : '';
                                $value['unit'] = $val['单位'];
                                $value['batchnumber'] = $val['批号'] ? $val['批号'] : '';
                                $value['makedate'] = isset($makedate['date']) ? $makedate['date'] : (empty($makedate) ? '' : $makedate[0]);
                                $value['invalidate'] = isset($invalidate['date']) ? $invalidate['date'] : (empty($invalidate) ? '' : $invalidate[0]);
                                $value['registnum'] = $val['注册证号'] ? $val['注册证号'] : '';
                                $value['registivalidate'] = isset($registivalidate['date']) ? $registivalidate['date'] : (empty($registivalidate) ? '' : $registivalidate[0]);
                                $value['storagecondition'] = $val['储存条件'] ? $val['储存条件'] : '';
                                $value['status'] = isset($val['status']) ? $val['status'] : 0;
                                DB::table('medicinal')->insert($value);
                            }
                        }
					});
					admin_toastr('导入成功', 'success');
					return redirect('/admin/medicinals');
				} catch (\Exception $e) {
					return $e->getMessage();
				}
			}
		}
	}

	public function setPrice() {
		if (request()->isMethod('post')) {
			$excel = request()->file('excel');
			$ext_arr = ['xls', 'xlsx'];
			$re = $this->uploadFile($excel, $ext_arr);
			$hospital = request()->post('hospitalid');
			if ($re['status'] == 'error') {
				admin_toastr($re['msg'], $re['status']);
				return redirect('/admin/excel/setprice');
			} else {
				$filename = $re['info'];
				$medicinals = Medicinal::pluck('id', 'specification');
                $real_file = str_replace('\\','/','storage/' . $filename);
				if (!is_file($real_file)) {
					admin_toastr('文件不存在！', 'error');
					return redirect('/admin/excel/setprice');
				}
				try {
					Excel::load($real_file, function ($reader) use ($medicinals, $hospital) {
						$data = $reader->get()->toArray(true);
						$value = [];
						foreach($data as $k=>$v){
                            foreach ($v as $key => $val) {
                                if (!isset($val['规格型号']) || empty($val['规格型号'] || !isset($val['价格']) || empty($val['价格']))) {
                                    admin_toastr('excel表数据结构与模板不相符，请修改后再进行导入！', 'error');
                                    return redirect('/admin/excel/setprice');
                                }
                                $value['hospitalid'] = $hospital;
                                $value['medicinalid'] = $medicinals[$val['规格型号']];
                                $value['price'] = $val['价格'];
                                Hospitalprice::create($value);
                            }
                        }
					});
					admin_toastr('导入成功', 'success');
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
        }
        Order::where('id', $data['id'])->update(['orderinfo'=>json_encode($infos)]);
        admin_toastr('操作成功', 'success');
        return redirect('/admin/orders');
    }
}