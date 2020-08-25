<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::any('unAuth', function () {
	return response()->json(['status' => 401, '缺失api_token或者认证失败']);
})->name('unAuth');

Route::group(['namespace' => 'Api'], function () {
	//登陆
	Route::post('/login', 'IndexController@auth');
	//获取筛选厂家
	Route::get('getproducer','IndexController@producerList');
	//获取筛选产品线
    Route::get('getline','IndexController@lineList');
    //获取筛选分类
    Route::get('getcategories','IndexController@categoryList');
	//搜索页，包括筛选页面
	Route::get('/index', 'IndexController@indexSearch');
	//获取筛选项
	Route::get('/filter', 'IndexController@getFilter');
	//获取网站配置信息
	Route::get('/siteinfo', 'IndexController@getSiteInfo');
	//获取home页面三个筛选项
	Route::get('homefilter', 'IndexController@homeFilter');
	Route::group(['middleware' => 'auth:api'], function () {
		/**
		 * 该分组接口下必须带有api_token 字段，
		 */
		//登陆后，获取医院列表
		Route::get('/hospital', 'IndexController@hospitalList');
		//获取购物车列表
		Route::get('/cart', 'IndexController@myCart');
		//获取订单列表
		Route::get('/order', 'IndexController@myOrder');
		//订单详情
		Route::get('/orderinfo', 'IndexController@orderInfo');
		//退出登录
		Route::get('/logout', 'IndexController@logout');
		//获取商品规格
		Route::get('/specification', 'IndexController@getSpecification');
		//选择医院
		Route::post('/selecthospital', 'IndexController@selectHospital');
		//添加到购物车
		Route::post('/addcart', 'IndexController@addCart');
		//修改购物车商品数量
		Route::post('/changenum', 'IndexController@changeNum');
		//删除购物车商品
		Route::post('/delcart', 'IndexController@delCartMedicinals');
		//提交订单下单
		Route::post('/addorder', 'IndexController@addOrder');
		//修改密码
		Route::post('/changepwd', 'IndexController@changePassword');

	});
});