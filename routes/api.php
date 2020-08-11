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
	Route::post('/login', 'IndexController@auth');
	Route::get('/index', 'IndexController@indexSearch');
	Route::get('/filter', 'IndexController@getFilter');
	Route::get('/siteinfo', 'IndexController@getSiteInfo');
	Route::group(['middleware' => 'auth:api'], function () {
		Route::get('/hospital', 'IndexController@hospitalList');
		Route::get('/cart', 'IndexController@myCart');
		Route::get('/order', 'IndexController@myOrder');

		Route::post('/selecthospital', 'IndexController@selectHospital');
		Route::post('/addcart', 'IndexController@addCart');
		Route::post('/changenum', 'IndexController@changeNum');
		Route::post('/delcart', 'IndexController@delCartMedicinals');
		Route::post('/addorder', 'IndexController@addOrder');
		Route::post('/changepwd', 'IndexController@changePassword');
		Route::post('/logout', 'IndexController@logout');
	});
});