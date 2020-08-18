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

class IndexController extends Controller {
	use AuthenticatesUsers;
	public $user;
	public $hospital;
	public function __construct(Request $request) {
		$this->user = Auth::guard('api')->user();
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
		//$images = json_decode($info['banners'], true);
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
		$userinfo = $this->checkSession();
		if ($this->attemptLogin($request)) {
			$user = $this->guard()->user();
			$user->generateToken();
			session()->put('user.info', $user->toArray());
			$this->user = $user;
			return $this->successData('登陆成功', ['user' => session('user.info')]);
		}
		return $this->errorData('登陆失败');
	}

	protected function checkSession() {
		$userinfo = session('user.info');

		if (!$userinfo) {
			$userinfo = Salelist::where('api_token', request()->get('api_token'))->first()->toArray(true);
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
		$uid = $userinfo['id'];
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
		$this->hospital = $hospitalinfo;
		session()->put('user.hospital', $hospitalinfo->toArray());
		return $this->successData('选择医院成功', ['hospital' => $hospitalinfo]);
	}

	/**
	 * [厂家列表]
	 * @return [type] [description]
	 */
	public function producerList() {
		$producer = Producer::pluck('name');
		return $this->successData('厂家', []);
	}

	/**
	 * [产品线列表]
	 * @return [type] [description]
	 */
	public function lineList() {
		$lines = Productline::pluck('linename');
		return $this->successData('产品线', []);
	}

	/**
	 * [分类列表]
	 * @return [type] [description]
	 */
	public function categoryList() {
		$categories = Category::pluck('categoryname');
		return $this->successData('分类', ['caetgory' => $categories]);
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
					$db->orWhere('medicinal', 'like', '%' . $q . '%')->orWhere('specification', $q);
				}
			})->paginate(20);
		$data = $data->toArray(true);
		$this->user = $this->checkSession();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['line'] = Productline::where('id', $value['line_id'])->value('linename');
			$data['data'][$key]['category'] = Category::where('id', $value['category_id'])->value('categoryname');
			$producer = Producer::where('id', $value['producer_id'])->value('name');
			$data['data'][$key]['newname'] = $producer . ' ' . $value['medicinal'];
			$data['data'][$key]['unit'] = $value['unit'];
			$data['data'][$key]['stocks'] = $value['stock'];
		}
		if ($this->user['type'] == 2) {
			//医院用获取医院价格
			if ($request['hid']) {
				$this->hospital = Hospital::find($request['hid']);
				foreach ($data['data'] as $key => $val) {
					$price = Hospitalprice::where([['hospitalid', $this->hospital['id']], ['medicinalid', $val['id']]])->value('price');
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
		$hospitalinfo = [];
		$lists = Mycart::where('buyerid', $userinfo['id'])->where(function ($model) use ($userinfo, $hid) {
			if ($userinfo['type'] == 2) {
				if ($hid != 0) {
					$hospitalinfo = Hospital::find($hid);
				} else {
					$hospitalinfo = session('user.hospital');
				}
				$model->where('hospitalid', $hospitalinfo['id']);
			}
		})->get();
		$data = [];
		foreach ($lists as $key => $value) {
			$medicinalinfo = Medicinal::where('id', $value['medicinalid'])->get(['id', 'producer_id', 'medicinal', 'unit'])->first()->toArray(true);
			$producer = Producer::where('id', $medicinalinfo['producer_id'])->value('name');
			if ($userinfo['type'] == 2) {
				if ($hid != 0) {
					$hospitalinfo = Hospital::find($hid);
				} else {
					$hospitalinfo = session('user.hospital');
				}
				$price = Hospitalprice::where('hospitalid', $hospitalinfo['id'])->where('medicinalid', $medicinalinfo['id'])->value('price');
			} else {
				$price = $value['price'];
			}
			$data[] = [
				'id' => $value['id'],
				'medicinalid' => $value['medicinalid'],
				'name' => $producer . ' ' . $medicinalinfo['medicinal'] . $value['specification'],
				'price' => $price,
				'unit' => $medicinalinfo['unit'],
				'num' => $value['num'],
			];
		}
		return $this->successData('购物车', ['cart' => $data]);
	}

	/**
	 * 添加购物车
	 */
	public function addCart() {
		$userinfo = $this->checkSession();
		$request = request();
		$mid = $request['mid'];
		$specification = $request['specification'];
		$num = $request['num'];
		if ($userinfo['type'] == 2) {
			if ($request['hid']) {
				$hospitalinfo = Hospital::find($request['hid']);
			} else {
				$hospitalinfo = session('user.hospital');
			}
			$price = Hospitalprice::where([['hospitalid', $hospitalinfo['id']], ['medicinalid', $mid]])->value('price');
		} else {
			$price = $request['price'];
		}
		$isInCart = Mycart::where('buyerid', $userinfo['id'])->where('medicinalid', $mid)->first();
        $stock = Medicinal::where('id',$mid)->value('stock');
        if($stock <$num){
            return $this->errorData('添加失败,库存数量不足');
        }
		if (empty($isInCart)) {
			$data = [
				'buyerid' => $userinfo['id'],
				'specification' => $specification,
				'medicinalid' => $mid,
				'num' => $num,
				'price' => $price,
			];
			if ($request['hid']) {
				$data['hospitalid'] = $hospitalinfo['id'];
			}
			$result = Mycart::insert($data);
		} else {
			$result = Mycart::where('buyerid', $userinfo['id'])->where('medicinalid', $mid)->update(['num' => $num + $isInCart['num']]);
		}

		if ($result) {
			return $this->successData('添加成功!', []);
		} else {
			return $this->errorData('添加失败');
		}
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

	/**
	 * 下单
	 */
	public function addOrder() {
		$userinfo = $this->checkSession();
		$request = request();
		$lists = $request['data'];
		$data = [];
		$data['orderid'] = date('Ymd', time()) . rand(1000, 9999);
		$data['ordermonth'] = date('Ym', time());
		if ($userinfo['type'] == 2) {
			$data['orderstatus'] = 1;
			$hid = $request['hid'];
			if ($hid) {
				$hospitalinfo = Hospital::find($hid);
			} else {
				$hospitalinfo = session('user.hospital');
			}
			$data['hospital'] = $hospitalinfo['id'];
		} else {
			$data['orderstatus'] = 2;
		}
		$data['buyerid'] = $userinfo['id'];
		$data['buyertype'] = $userinfo['type'];
		$orderinfo = [];
		$total = 0;
		$totalnum = 0;
		$lists_arr = json_decode($lists, true);
		DB::beginTransaction();
		try{
            foreach ($lists_arr as $key => $val) {
                $medicinalid = myCart::where('id', $val['id'])->value('medicinalid');
                $medicinalinfo = Medicinal::find($medicinalid);
                if($medicinalinfo['stock'] < $val['num']){
                    DB::rollBack();
                    return $this->errorData('下单失败,'.$medicinalinfo['medicinal'].'数量不足');
                }
                $price = $val['price'] ? $val['price'] : $medicinalinfo['price'];
                $total += $val['num'] * $price;
                $totalnum += $val['num'];
                $info = [
                    'id' => $medicinalid,
                    'medicinal' => $medicinalinfo['medicinal'],
                    'specification' => $medicinalinfo['specification'],
                    'price' => $price,
                    'unit' => $medicinalinfo['unit'],
                    'num' => $val['num'],
                ];
                $medicinalinfo['stock'] = $medicinalinfo['stock'] - $val['num'];
                Medicinal::where('id', $medicinalid)->update(['stock' => $medicinalinfo['stock']]);
                Mycart::where('id', $val['id'])->delete();
                $orderinfo[] = $info;
            }

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
		$lists = Order::where('buyerid', $userinfo['id'])->get(['id', 'orderid', 'orderinfo', 'totalprice', 'orderstatus', 'created_at'])->orderBy('created_at', 'desc')->toArray(true);
		return $this->successData('订单', ['order' => $lists]);
	}

	public function orderInfo() {
		$request = request();
		$id = $request['oid'];
		$orderInfo = Order::where('id', $id)->first();
		$order_arr = json_decode($orderInfo['orderinfo'], true);
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

	/**
	 * 修改密码
	 * @return number[]|unknown[]
	 */
	public function changePassword() {
		$userinfo = $this->checkSession();
		$request = request();
		$password = $request['password'];
		$newpassword = $request['newpassword'];
		$userinfo['password'] = Salelist::where('id', $userinfo['id'])->value('password');
		if (!Hash::check($password, $userinfo['password'])) {
			return $this->errorData('原密码不正确');
		}
		Salelist::where('id', $userinfo['id'])->update(['password' => bcrypt($newpassword)]);
		DB::table('admin_users')->where('username', $userinfo['telephone'])->update(['password' => bcrypt($newpassword)]);
		$userinfo = Salelist::where('id', $userinfo['id'])->get()->toArray(true);
		return $this->successData('修改成功', ['user' => $userinfo]);
	}

	public function getSpecification() {
		$request = request();
		$mid = $request['mid'];
		$specification = Medicinal::where('id', $mid)->value('specification');
		return $this->successData('商品规格', ['specification' => $specification]);

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
