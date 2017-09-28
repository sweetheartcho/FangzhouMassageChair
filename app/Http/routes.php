<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::group(['middleware' => ['web']], function () {

});*/

use Illuminate\Routing\Route as IlluminateRoute;
use App\Helper\CaseInsensitiveUriValidator;
use Illuminate\Routing\Matching\UriValidator;

$validators = IlluminateRoute::getValidators();
$validators[] = new CaseInsensitiveUriValidator;
IlluminateRoute::$validators = array_filter($validators, function($validator) {
    return get_class($validator) != UriValidator::class;
});





// 全局范围用指定正则表达式限定路由参数
Route::pattern('id', '[0-9]+');

/*-- 默认显示登录页面 --*/
//Route::get('/','Admin\LoginController@index');

/*-- 后台主页 --*/
Route::group(['middleware' => ['logincheck']], function () {
    Route::any('Admin/Home/index', ['uses' => 'Admin\HomeController@index']);

    /*-- 跳转信息页面 --*/
    Route::resource('jump','Admin\JumpController');

    /*-- 用户管理列表页面 --*/
    Route::any('Admin/User/index', ['uses' => 'Admin\UserController@index']);
    /*-- 用户管理删除 --*/
    Route::any('Admin/User/delete/id/{id}', ['uses' => 'Admin\UserController@delete']);
    /*-- 用户管理禁用或启用 --*/
    Route::any('Admin/User/stopOrStart/id/{id}', ['uses' => 'Admin\UserController@stopOrStart']);

    /*-- 商户管理删除 --*/
    Route::any('Admin/Company/delete/id/{id}', ['uses' => 'Admin\CompanyController@delete']);
    /*-- 商户编辑页面 --*/
    Route::any('Admin/Company/companyEditIndex/id/{id}', ['uses' => 'Admin\CompanyController@companyEditIndex']);

    /*-- 服务员查看页面 --*/
    Route::any('Admin/Waiter/index/id/{id}', ['uses' => 'Admin\WaiterController@index']);
    /*-- 服务员删除 --*/
    Route::any('Admin/Waiter/delete/id/{id}', ['uses' => 'Admin\WaiterController@delete']);
    /*-- 服务员信息编辑 --*/
    Route::any('Admin/Waiter/waiterEditIndex/id/{id}', ['uses' => 'Admin\WaiterController@waiterEditIndex']);
    /*-- 服务员添加页面 --*/
    Route::any('Admin/Waiter/waiterAddIndex/id/{id}', ['uses' => 'Admin\WaiterController@waiterAddIndex']);

    /*-- 贵宾厅列表删除 --*/
    Route::any('Admin/Merchant/delete/id/{id}', ['uses' => 'Admin\MerchantController@delete']);
    /*-- 贵宾厅信息编辑页面 --*/
    Route::any('Admin/Merchant/merchantEditIndex/id/{id}', ['uses' => 'Admin\MerchantController@merchantEditIndex']);

    /*-- 商品管理令牌删除 --*/
    Route::any('Admin/Card/delete/card_id/{card_id}', ['uses' => 'Admin\CardController@delete']);
    /*-- 商品管理编辑页面 --*/
    Route::any('Admin/Card/cardEditIndex/card_id/{card_id}', ['uses' => 'Admin\CardController@cardEditIndex']);
    /*-- 商品管理上下架 --*/
    Route::any('Admin/Card/stopOrStart/card_id/{card_id}', ['uses' => 'Admin\CardController@stopOrStart']);






    Route::any('/{module}/{class}/{action}', function ($module, $class, $action) {
        $ctrl = \App::make("\\App\\Http\\Controllers\\" . $module . "\\" . $class . "Controller");
        return \App::call([$ctrl, $action]);
    });

});