<?php

use Illuminate\Routing\Router;
Admin::routes();
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    //$router->resource('/admin', 'Admin\AdminController');
    $router->resource('producers', ProducerController::class);
    $router->resource('productlines', ProductlineController::class);
    $router->resource('usertypes', UsertypeController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('distributors', DistributorController::class);
    $router->resource('salesmen', SalesmanController::class);
    $router->resource('hospitals', HospitalController::class);
    $router->resource('medicinals', MedicinalController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('salelists', SalelistController::class);
    $router->resource('dollar', HospitalpriceController::class);
    $router->resource('templates', TemplateController::class);
    $router->resource('products', ProductController::class);
    $router->resource('/sites', SiteController::class);
    //自定义页面
    
    
    $router->get('/api/line', 'ApiController@line');
    $router->get('/api/category', 'ApiController@category');
    $router->get('/password/setadmin', 'PasswordController@setAdmin');
    $router->get('/password/setpwd/{id}', 'PasswordController@setPwdByUserId');
    $router->get('/excel', 'ExcelController@excel');
    $router->get('/excel/setprice', 'ExcelController@setPirce');
    $router->get('/excel/medicinals', 'ExcelController@medicinals');
    $router->get('/excel/changeprice', 'ExcelController@changePrice');
    $router->get('/api/getmedicinals','ApiController@getMedicinals');
    $router->get('/setting/info','SettingController@info');
    $router->get('/setting/prints','SettingController@selectPrint');
    $router->get('/print/hostpital','PrintsController@hospitalPrint');
    $router->get('/print/jxs','PrintsController@jxsPrint');
    $router->get('/print/finance','PrintsController@finance');
    $router->get('/api/gifts','ApiController@gifts');
    $router->get('/excel/updateattr', 'ExcelController@updateAttr');
    $router->get('excel/order', 'ExcelController@Order');
    $router->get('excel/splitorder', 'ExcelController@splitOrder');
    $router->get('/excel/fenpi', 'ExcelController@fenpiOrder');
    $router->get('excel/shipping', 'ExcelController@startShipping');
    $router->get('/api/defaultsplit', 'ApiController@defaultSplitOrder');
    $router->get('/api/finaceprint', 'ApiController@updateFinacePrintStatus');
    $router->get('/api/finaceexport', 'ApiController@updateFinaceExportStatus');

    //自定义接收数据页面
    $router->post('/password/setadmin', 'PasswordController@setAdmin');
    $router->post('/password/setpwd', 'PasswordController@setPwd');
    $router->post('/api/medicinals', 'ApiController@medicinals');
    $router->post('/api/medicinalstatus', 'ApiController@medicinalStatus');
    $router->post('/api/setprice', 'ApiController@setPrice');
    $router->post('/api/changestatus', 'ApiController@changeOrderStatus');
    $router->post('/api/account', 'ApiController@account');
    $router->post('/api/gift', 'ApiController@orderGift');
    $router->post('/api/changeprice', 'ApiController@changeOrderInfoPrice');
    $router->post('/api/changeinfo', 'ApiController@changeInfo');
    $router->post('/sites/siteinfo', 'SiteController@siteInfo');
    $router->post('/api/searchm', 'ApiController@searchmedicinal');
    $router->post('/api/updateattr', 'ApiController@updateAttr');
    $router->post('/api/orders', 'ApiController@Orders');
    $router->post('/api/splitorder', 'ApiController@splitOrder');
    $router->post('/api/fenpiorder', 'ApiController@fenpiOrder');
    $router->post('/api/shipping', 'ApiController@shipping');
});
