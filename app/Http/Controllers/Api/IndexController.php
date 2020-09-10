<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Hospital;
use App\Hospitalprice;
use App\Http\Controllers\Controller;
use App\Medicinal;
use App\Mycart;
use App\Order;
use App\Producer;
use App\Productline;
use App\Salelist;
use App\Site;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller {
	use AuthenticatesUsers;
	public $user;
	public $hospital;
	public function __construct(Request $request) {
		$this->user = Auth::user();
		if (empty($this->user)) {
			return $this->errorData('请登录');
		}
		$token = $request['token'];
		if ($token != $this->user['_token']) {
			return $this->errorData('_token不匹配');
		};
	}

	public function username() {
		return 'telephone';
	}

	public function getSiteInfo() {
		$info = Site::find(1);
		$images = [];
		foreach ($info['banners'] as $key => $value) {
			$images[] = env('APP_URL') . '/storage/' . $value;
		}
		$info['banners'] = $images;
		return $this->successData('全站信息', ['site' => $info]);
	}

	/**
	 * 登陆认证
	 * @param Request $request
	 * @return number[]|unknown[]|number[]|unknown[]|string[]
	 */
	public function auth(Request $request) {
        $status = Salelist::where('telephone', $request->telephone)->value('status');
        if($status ==1){
            return $this->errorData('该账号已被冻结');
        }
        $password = Salelist::where('telephone', $request->telephone)->value('password');
        if(!$password){
            return $this->errorData('该账号不存在');
        }
		if (Auth::attempt(['telephone'=>$request->telephone,'password'=>$request->password])){
			$user = $this->guard('api')->user();
            $user->generateToken();
            //$user->type = Salelist::where('telephone', request()->get('telephone'))->value('type');
            $userinfo = Auth::user();
            $this->user = $userinfo;
            return $this->successData('登陆成功', ['user' => $userinfo]);
		}
		return $this->errorData('登陆失败');
	}

	protected function checkSession() {
		$userinfo = Auth::user();
		$request = request();
		if (!$userinfo) {
			$userinfo = Salelist::where('api_token', $request->telephone)->first();
			if($userinfo->status ==1){
                Salelist::where('telephone', $userinfo->telephone)->update(['api_token'=>'']);
			    return $this->errorData('该账号已被冻结');
            }
			if (empty($userinfo)) {
				return $this->errorData('登陆失效');
			} else {
				return $userinfo;
			}
		} else {
			return $userinfo;
		}
	}

	/**
	 * 医院列表
	 * @return number[]|unknown[]
	 */
	public function hospitalList() {
		$userinfo = $this->checkSession();
		$uid = $userinfo->id;
		$lists = Hospital::where('belongto', $uid)->pluck('hospital', 'id')->toArray(true);
		return $this->successData('医院列表', ['hospital' => $lists]);
	}

	/**
	 * 选择医院
	 * @param Request $request
	 * @return number[]|unknown[]|string[]|number[]|unknown[]
	 */
	public function selectHospital(Request $request) {
		$userinfo = $this->checkSession();
		$hid = $request['hid'];
		$hospitalinfo = Hospital::find($hid);
		if (empty($hospitalinfo)) {
			return $this->errorData('不存在的数据');
		}
		return $this->successData('选择医院成功', ['hospital' => $hospitalinfo]);
	}

	/**
	 * [厂家列表]
	 * @return [type] [description]
	 */
	public function producerList() {
		$producer = Producer::pluck('name')->toArray(true);
        array_splice($producer, 0, 0, '厂家');
		return $this->successData('厂家', ['list'=>$producer]);
	}

	/**
	 * [产品线列表]
	 * @return [type] [description]
	 */
	public function lineList(Request $request) {
	    $pname = $request->pid;
	    $pid = Producer::where('name',$pname)->value('id');
		$lines = Productline::where('producer_id', $pid)->pluck('linename')->toArray(true);
        array_splice($lines, 0, 0, '产品线');
		return $this->successData('产品线', ['list'=>$lines]);
	}

	/**
	 * [分类列表]
	 * @return [type] [description]
	 */
	public function categoryList(Request $request) {
	    $lname = $request->lid;
	    $lid = Productline::where('linename',$lname)->value('id');
		$categories = Category::where('line_id', $lid)->pluck('categoryname')->toArray(true);
        array_splice($categories, 0, 0, '分类');
		return $this->successData('分类', ['list' => $categories]);
	}

	public function homeFilter() {
		$producer = Producer::where('is_top', 1)->get(['id', 'name', 'image'])->take(3)->toArray(true);
		foreach ($producer as $key => $value) {
			$producer[$key]['line'] = Productline::where('producer_id', $value['id'])->value('linename');
			$producer[$key]['image'] = env('APP_URL') . '/storage/' . $value['image'];
		}
		return $this->successData('首页筛选位', ['filter' => $producer]);
	}

	public function getFilter() {
		$producer = Producer::pluck('name')->toArray(true);
		$lines = Productline::pluck('linename')->toArray(true);
		$categories = Category::pluck('categoryname')->toArray(true);
		array_splice($producer, 0, 0, '厂家');
		array_splice($lines, 0, 0, '产品线');
		array_splice($categories, 0, 0, '分类');
		return $this->successData('筛选', [$producer, $lines, $categories]);
	}

	/**
	 * 搜索，筛选，搜索
	 * @return number[]|unknown[]
	 */
	public function indexSearch() {
		$request = request();
		$pid = isset($request['pid']) ? $request['pid'] : '';
		$lid = isset($request['lid']) ? $request['lid'] : '';
		$cid = isset($request['cid']) ? $request['cid'] : '';
		$q = isset($request['q']) ? $request['q'] : '';
		$data = [];
		$producerid = '';
		$lineid = '';
		$categoryid = '';
		if ($pid) {
			$producerid = Producer::where('name', $pid)->value('id');
		}
		if ($lid) {
			$lineid = Productline::where('linename', $lid)->value('id');
		}
		if ($cid) {
			$categoryid = Category::where('categoryname', $cid)->value('id');
		}
		$data = Medicinal::where('status', 0)->where(function ($db) use ($producerid, $lineid, $categoryid) {
			if ($producerid != '') {
				$db->where('producer_id', $producerid)->where(function ($db) use ($lineid, $categoryid) {
					if ($lineid != '') {
						$db->where('line_id', $lineid)->where(function ($db) use ($categoryid) {
							if ($categoryid != '') {
								$db->where('category_id', $categoryid);
							}
						});
					} else {
						if ($categoryid != '') {
							$db->where('category_id', $categoryid);
						}
					}
				});
			} else {
				$db->where(function ($db) use ($lineid, $categoryid) {
					if ($lineid != '') {
						$db->where('line_id', $lineid)->where(function ($db) use ($categoryid) {
							if ($categoryid != '') {
								$db->where('category_id', $categoryid);
							}
						});
					} else {
						if ($categoryid != '') {
							$db->where('category_id', $categoryid);
						}
					}
				});
			}
		})
			->where(function ($db) use ($q) {
				if ($q != '') {
					$db->orWhere('medicinal', 'like', '%' . $q . '%')->orWhere('specification', 'like', '%' . $q . '%')->orWhere('medicinalnum', 'like', '%' . $q . '%');
				}
			})->paginate(20);
		$data = $data->toArray(true);
		$userinfo = $this->checkSession();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['line'] = Productline::where('id', $value['line_id'])->value('linename');
			$data['data'][$key]['category'] = Category::where('id', $value['category_id'])->value('categoryname');
			$producer = Producer::where('id', $value['producer_id'])->value('name');
			$data['data'][$key]['newname'] = $producer . ' ' . $value['medicinal'];
			$data['data'][$key]['unit'] = $value['unit'];
			$data['data'][$key]['stocks'] = $value['stock'];
		}

		if (isset($userinfo->type) &&$userinfo->type == 2) {
			//医院用获取医院价格
			if ($request['hid']) {
				$hospital = Hospital::find($request['hid']);
				foreach ($data['data'] as $key => $val) {
					$price = Hospitalprice::where([['hospitalid', $hospital->id], ['medicinalid', $val['id']]])->value('price');
					$data['data'][$key]['price'] = $price;
				}
			}
		}
		return $this->successData('搜索', ['list' => $data]);
	}

	/**
	 * 购物车列表
	 */
	public function myCart() {
		$request = request();
		$hid = isset($request['hid']) ? $request['hid'] : 0;
		$userinfo = $this->checkSession();
        if (isset($userinfo->type) && $userinfo->type == 2) {
            if ($hid == 0) {
                return $this->successData('请选择医院',['cart'=>[]]);
            }
        }
		$hospitalinfo = [];
		$lists = Mycart::where('buyerid', $userinfo->id)->where(function ($model) use ($userinfo, $hid) {
			if (isset($userinfo->type) && $userinfo->type == 2) {
				if ($hid != 0) {
					$hospitalinfo = Hospital::find($hid);
				}else{
                    return $this->successData('请选择医院',['cart'=>[]]);
                }
				$model->where('hospitalid', $hospitalinfo->id);
			}
		})->get();
		$data = [];
		foreach ($lists as $key => $value) {
			$medicinalinfo = Medicinal::where('id', $value['medicinalid'])->get(['id', 'producer_id', 'medicinal', 'unit'])->first();
			$producer = Producer::where('id', $medicinalinfo['producer_id'])->value('name');
            if (isset($userinfo->type) && $userinfo->type == 2) {

                if ($hid != 0) {
					$hospitalinfo = Hospital::find($hid);
				}else{
                    return $this->successData('请选择医院',['cart'=>[]]);
                }
				$price = Hospitalprice::where('hospitalid', $hospitalinfo->id)->where('medicinalid', $medicinalinfo['id'])->value('price');
			} else {
				$price = $value['price'];
			}
			$data[] = [
				'id' => $value['id'],
				'medicinalid' => $value['medicinalid'],
				'name' => $producer . ' ' . $medicinalinfo['medicinal'] . $value['medicinalnum'],
				'price' => $price,
				'unit' => $medicinalinfo['unit'],
				'num' => $value['num'],
                't_price' => $price * $value['num'],
                'gift'=>[
                    'id' => $value['originid'],
                    'originname'=>Medicinal::where('id',$value['originid'])->value('medicinal'),
                    'originnum' => $value['originnum'],
                    'medicinalnum' => Medicinal::where('id',$value['originid'])->value('medicinalnum')
                ]
			];
		}
		return $this->successData('购物车', ['cart' => $data]);
	}

	public function delGift(Request $request){
	    $carid = $request->id;
	    DB::table('mycart')->where('id',$carid)->update(['originid'=>null,'originnum'=>null]);
	    return $this->successData('删除成功',[]);
    }

	/**
	 * 添加购物车
	 */
	public function addCart() {
		$userinfo = $this->checkSession();
		$request = request();
		$mid = $request['mid'];
		$medicinalnum = $request['medicinalnum'];
		$originid = isset($request['originid'])?$request['originid']:null;
		$originnum = isset($request['originnum'])?$request['originnum']:null;
		$num = $request['num'];
        if (isset($userinfo->type) && $userinfo->type == 2) {
            if ($request['hid']) {
				$hospitalinfo = Hospital::find($request['hid']);
			} else {
				return $this->errorData('请选择医院',['cart'=>[]]);
			}
			$price = Hospitalprice::where([['hospitalid', $hospitalinfo->id], ['medicinalid', $mid]])->value('price');
		} else {
			$price = $request['price'];
		}
		$isInCart = Mycart::where('buyerid', $userinfo->id)->where([['medicinalid', $mid],['price',$price]])->first();

		if (empty($isInCart)) {
			$data = [
				'buyerid' => $userinfo->id,
				'medicinalnum' => Medicinal::where('id', $mid)->value('medicinalnum'),
				'medicinalid' => $mid,
				'num' => $num,
				'price' => $price,
                'originid'=>$originid,
                'originnum' => $originnum
			];
			if ($request['hid']) {
				$data['hospitalid'] = $hospitalinfo->id;
			}
			$result = Mycart::insert($data);
		} else {
			$result = Mycart::where('buyerid', $userinfo->id)->where([['medicinalid', $mid],['price',$price]])->update(['num' => $num + $isInCart->num]);
		}

		if ($result) {
			return $this->successData('添加成功!', []);
		} else {
			return $this->errorData('添加失败');
		}
	}

	public function searchGift(Request $request){
        $q = $request->q;
        $lists = Medicinal::where([['status',0],['medicinalnum','like','%'.$q.'%']])->simplePaginate(20);
        return $this->successData('赠品搜索页',['data'=>$lists]);
    }

	public function addGift(Request $request){
	    $userinfo = $this->checkSession();
	    if($userinfo->type !=2){
	        return $this->errorData('只有业务员才能设置赠品');
        }
	    $carid = $request->id;
	    $originid = $request->originid;
	    $num = $request->num;
	    DB::table('mycart')->where('id', $carid)->update(['originid'=>$originid, 'originnum'=>$num]);
	    return $this->successData('赠品添加成功',[]);
    }
	/**
	 * 修改购物车商品数量
	 */
	public function changeNum() {
		$userinfo = $this->checkSession();
		$request = request();
		$cartid = $request['id'];
		$num = $request['num'];
		$result = Mycart::where('id', $cartid)->update(['num' => $num]);
		if ($result) {
			return $this->successData('修改成功!', []);
		} else {
			return $this->errorData('修改失败');
		}
	}

	/**
	 * 删除购物车中的商品
	 */
	public function delCartMedicinals() {
		$userinfo = $this->checkSession();
		$request = request();
		$cartid = $request['id'];
		$cartid_arr = explode(',', $cartid);
		foreach ($cartid_arr as $key => $val) {
			Mycart::where('id', $val)->delete();
		}
		return $this->successData('删除成功!', []);
	}

	public function uploadExcel(Request $request){
        $File = $request->file('file');
        $option = ['xls','xlsx'];
        $fileExtension = $File->getClientOriginalExtension();
        if (!in_array($fileExtension, $option)) {
            throw new \Exception('文件类型不正确，只能上传.xls或者.xlsx后缀的文件');
        }
        $tmpFile = $File->getRealPath();
        if (!is_uploaded_file($tmpFile)) {
            throw new \Exception('非法上传途径');
        }
        $fileName = date('Y_m_d') . '/' . md5(time()) . mt_rand(0, 9999) . '.' . $fileExtension;
        Storage::disk('public')->put($fileName, file_get_contents($tmpFile));
        $realfile = $real_file = str_replace('\\','/','storage/' . $fileName);
        if (!is_file($realfile)) {
            throw new \Exception('文件上传失败！');
        }
        return $this->successData('上传成功',['file'=>$realfile]);
    }

	public function importCart(Request $request){
	    $userinfo = $this->checkSession();
        $hid = $request->hid?$request->hid:0;
        if($userinfo->type == 2){
	        if(!$hid){
                throw new \Exception('请选择医院后再导入');
            }
        }
        $realfile = $request->file;
        try{
            Excel::load($realfile, function($reader)use($userinfo,$hid){
                $insertData = [];
                $data = $reader->get()->toArray(true);
                foreach ($data as $key=>$value){
                    foreach ($value as $k=>$v){
                        if(!isset($v['产品货号']) || empty($v['产品货号']) || $v['产品货号'] == 'null' ){
                            //throw new \Exception('第'.($k+2).'行产品货号不能为空');
                            continue;
                        }
                        if(!isset($v['数量']) || empty($v['数量']) || $v['数量'] == 'null' ){
                            //throw new \Exception('第'.($k+2).'行数量不能为空');
                            continue;
                        }
                        if($userinfo->type == 1){
                            if(!isset($v['价格']) || empty($v['价格']) || $v['价格'] == 'null' ){
                                //throw new \Exception('第'.($k+2).'行价格不能为空');
                                continue;
                            }
                        }

                        $medicinalinfo = Medicinal::where('medicinalnum', $v['产品货号'])->first();
                        if(!$medicinalinfo){
                            //throw new \Exception('第'.($k+2).'行产品货号为：'.$v['产品货号'].'的产品在产品库不存在');
                            continue;
                        }
                        $_info = [
                            'medicinalid'=> $medicinalinfo->id,
                            'medicinalnum' => $medicinalinfo->medicinalnum,
                            'num' => $v['数量'],
                            'buyerid' => $userinfo->id,
                            'originid' => null,
                            'originnum' => null
                        ];
                        if($userinfo->type == 2){
                            if(isset($v['赠品货号']) && $v['赠品货号'] != 'null'){
                                if(!isset($v['赠品数量']) || empty($v['赠品数量']) || $v['赠品数量'] == 'null' ){
                                    throw new \Exception('第'.($k+2).'行赠品数量列不存在');
                                }
                                $giftorigin = Medicinal::where('medicinalnum', $v['赠品货号'])->first();
                                $_info['originid'] = $giftorigin->id;
                                $_info['originnum'] = $v['赠品数量'];
                            }
                            $price = Hospitalprice::where([['hospitalid',$hid],['medicinalid',$medicinalinfo->id]])->value('price');
                            $_info['price'] = $price;
                            $_info['hospitalid'] = $hid;
                        }else{
                            $_info['price'] = $v['价格'];
                        }
                        $insertData[] = $_info;
                    }
                }
                DB::table('mycart')->insert($insertData);
            });
            return $this->successData('导入成功',[]);
        }catch(\Exception $e){
            return $e->getMessage();
        }

    }

	/**
	 * 下单
	 */
	public function addOrder() {
		$userinfo = $this->checkSession();
		$request = request();
		$lists = $request['data'];
		$gift = $request->gift;
		$data = [];
		$data['orderid'] = date('Ymd', time()) . rand(1000, 9999);
		$data['ordermonth'] = date('Ym', time());
        if (isset($userinfo->type) && $userinfo->type == 2) {

            $data['orderstatus'] = 1;
			$hid = $request['hid'];
			if ($hid) {
				$hospitalinfo = Hospital::find($hid);
			} else {
                return $this->successData('请选择医院',['cart'=>[]]);
			}
			$data['hospital'] = $hospitalinfo->id;
		} else {
			$data['orderstatus'] = 3;
		}
		$data['buyerid'] = $userinfo['id'];
		$data['buyertype'] = $userinfo['type'];
		$orderinfo = [];
        $giftinfo = [];
		$total = 0;
		$totalnum = 0;
		$lists_arr = json_decode($lists, true);
		DB::beginTransaction();
		try{
            foreach ($lists_arr as $key => $val) {
                $cartinfo = myCart::where('id', $val['id'])->first();
                $total += $cartinfo['price']*$cartinfo['num'];
                $totalnum += $cartinfo['num'];
                $medicinalinfo = Medicinal::find($cartinfo['medicinalid']);
                $info = [
                    'id'=>$medicinalinfo->id,
                    'medicinal' => $medicinalinfo->medicinal,
                    'medicinalnum' => $medicinalinfo->medicinalnum,
                    'price'=> $cartinfo->price,
                    'unit' => $medicinalinfo->unit,
                    'num' => $cartinfo->num,
                ];
                $orderinfo[] = $info;
                if($cartinfo->originid){
                    $ginfo = [
                        'id'=>$cartinfo->originid,
                        'num' => $cartinfo->originnum,
                        'originid'=>$cartinfo->medicinalid
                    ];
                    $giftinfo[] = $ginfo;
                }
                Mycart::where('id', $val['id'])->delete();
            }
            $data['gift'] = json_encode($giftinfo);
            $data['totalprice'] = $total;
            $data['orderinfo'] = json_encode($orderinfo);
            $data['created_at'] = date('Y-m-d H:i:s', time());
            $result = Order::insertGetId($data);
            DB::commit();
        }catch (\Exception $e){
		    DB::rollBack();
		    return $this->errorData('下单失败,'.$e->getMessage());
        }
		$responseData = ['id' => $result, 'orderid' => $data['orderid'], 'total' => $total, 'totalnum' => $totalnum, 'ordertime' => date('Y-m-d H:i:s', time())];
		if ($result) {
			return $this->successData('下单成功!', ['orderinfo' => $responseData]);
		} else {
			return $this->errorData('下单失败');
		}
	}

	/**
	 * 我的订单
	 * @return number[]|unknown[]
	 */
	public function myOrder() {
		$userinfo = $this->checkSession();
		if($userinfo->type == 2){
            $hid = request()->hid;
            $lists = Order::where([['buyerid', $userinfo->id],['hospital',$hid]])->orderBy('id', 'desc')->get(['id', 'orderid', 'gift','orderinfo', 'totalprice', 'orderstatus', 'created_at'])->toArray(true);
        }else{
            $lists = Order::where([['buyerid', $userinfo->id]])->orderBy('id', 'desc')->get(['id', 'orderid', 'orderinfo', 'totalprice', 'orderstatus', 'created_at'])->toArray(true);
        }
		foreach ($lists as $key=>$list){
            if($userinfo->type == 2){
            $gift = json_decode($list['gift'], true);
            $g = [];
                foreach ($gift as $k=>$v){
                    $medicinal = Medicinal::where('id',$v['id'])->first();
                    //print_r(DB::getQueryLog());
                    $origin = Medicinal::where('id',$v['originid'])->first();
                    $g[$k]['medicinal'] = $medicinal['medicinal'];
                    $g[$k]['medicinalnum'] = $medicinal['medicinalnum'];
                    $g[$k]['num'] = $v['num'];
                    $g[$k]['origin'] = $origin['medicinal'].'/'.$origin['medicinalnum'];
                }
                $lists[$key]['gift'] = json_encode($g);
            }
        }
		return $this->successData('订单', ['order' => $lists]);
	}

	public function orderInfo() {
        $userinfo = $this->checkSession();
		$request = request();
		$id = $request['oid'];
		$orderInfo = Order::where('id', $id)->first();
		$order_arr = json_decode($orderInfo->orderinfo, true);
		$total = 0;
		$totalnum = 0;
		foreach ($order_arr as $key => $value) {
			$total += $value['price'] * $value['num'];
			$totalnum += $value['num'];
		}
		$orderInfo['total'] = $total;
		$orderInfo['totalnum'] = $totalnum;
		if ($orderInfo) {
			return $this->successData('订单详情!', ['info' => json_encode($orderInfo)]);
		} else {
			return $this->errorData('查询失败');
		}
	}

	public function confirmOrder(){
        $userinfo = $this->checkSession();
	    $request = request();
	    $orderid = $request->id;
	    DB::table('orders')->where('id', $orderid)->update(['orderstatus'=>3,'updated_at'=>date('Y-m-d H:i:s', time())]);
	    return $this->successData('确认订单',[]);
    }

	/**
	 * 修改密码
	 * @return number[]|unknown[]
	 */
	public function changePassword() {
		$userinfo = $this->checkSession();
		$request = request();
		$password = $request['password'];
		$newpassword = $request['newpassword'];
		$userinfo['password'] = Salelist::where('id', $userinfo->id)->value('password');
		if (!Hash::check($password, $userinfo->password)) {
			return $this->errorData('原密码不正确');
		}
		Salelist::where('id', $userinfo->id)->update(['password' => bcrypt($newpassword)]);
		$userinfo->password = bcrypt($newpassword);
		return $this->successData('修改成功', ['user' => $userinfo]);
	}

	public function getSpecification() {
		$request = request();
		$mid = $request['mid'];
		$medicinalnum = Medicinal::where('id', $mid)->value('medicinalnum');
		return $this->successData('商品规格', ['medicinalnum' => $medicinalnum]);

	}

	/**
	 * 退出
	 * @param Request $request
	 * @return number[]|unknown[]
	 */
	public function logout(Request $request) {
		$user = Auth::guard('api')->user();
		if ($user) {
			$user->api_token = null;
			$user->save();
		}
		return $this->successData('退出成功');
	}
}
