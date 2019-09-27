<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/', function () {
//     return view('/','welcome',['name'=>'小爱']);
// });

// Route::get('/', function () {
//     return '<form action="/doAdd" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="text name="user_name"><button>提交</button>';
// });

// Route::post('doAdd',function(){
// 	dd(request()->user_name);
// });

// match支持多种路由
// Route::match(['post','get'],'/doAdd',function(){
// 	dd(request()->username);
// });

// any支持多种路由
// Route::any('/doAdd',function(){
// 	dd(request()->username);
// });

// Route::get('user/{id?}',function($id=90){
// 	echo $id;
// })->name('uid');

// Route::get('/aa',function(){
// 	return redirect()->route('uid');
// });

/********************************* cookie ************************************/
// Route::get('cookie/add', function () {
//  	$minutes = 24 * 60;
//  	return response('欢迎来到 Laravel 学院')->cookie('name', '花Q！', $minutes);
// });
// Route::get('cookie/get', function(\Illuminate\Http\Request $request) {
//  	$cookie = $request->cookie('name');
//  	dd($cookie);
// });
/**********************************************************************************/

/************************** 学生管理 ***********************************************/
Route::get('student/add','admin\StudentController@add');			// 学生添加
Route::post('student/do_add','admin\StudentController@do_add');		// 学生添加处理
Route::get('student/list','admin\StudentController@list');			// 学生列表
/**********************************************************************************/


/************************** 发送邮件 ***********************************************/
Route::get('mail','MailController@index');
/**********************************************************************************/


/************************** 后台登陆 ***********************************************/
Route::prefix('admin')->group(function(){
	Route::get('login','admin\LoginController@login');					// 登陆视图
	Route::post('do_login','admin\LoginController@do_login');			// 登陆处理
});
/**********************************************************************************/


/************************** 用户管理 ***********************************************/
Route::prefix('user')->middleware('checklogin')->group(function(){
	Route::get('list','admin\UserController@list'); 				// 用户列表
	Route::get('create','admin\UserController@create'); 			// 用户添加
	Route::post('save','admin\UserController@save'); 				// 用户添加入库
	Route::get('delete/{id}','admin\UserController@delete');		// 用户删除
	Route::get('edit/{id}','admin\UserController@edit');			// 用户修改
	Route::post('update/{id}','admin\UserController@update');		// 用户修改入库
});
/**********************************************************************************/


/************************** 品牌管理 ***********************************************/
Route::prefix('brand')->middleware('checklogin')->group(function(){
	Route::get('list','admin\BrandController@list');				// 品牌列表
	Route::get('create','admin\BrandController@create');			// 品牌添加
	Route::post('save','admin\BrandController@save');				// 品牌添加入库
	Route::get('delete/{id}','admin\BrandController@delete');		// 品牌删除
	Route::get('edit/{id}','admin\BrandController@edit');			// 品牌修改
	Route::post('update/{id}','admin\BrandController@update'); 		// 品牌修改入库
	Route::get('brand_list','admin\BrandController@brand_list');
});
/**********************************************************************************/


/************************** 分类管理 ***********************************************/
Route::prefix('category')->middleware('checklogin')->group(function(){
	Route::get('list','admin\CategoryController@list');				// 分类列表
	Route::get('create','admin\CategoryController@create');			// 分类添加
	Route::get('save','admin\CategoryController@save');				// 分类添加入库
	Route::get('delete/{id}','admin\CategoryController@delete');	// 分类删除
	Route::get('edit/{id}','admin\CategoryController@edit');		// 分类修改
	Route::post('update/{id}','admin\CategoryController@update');	// 分类修改入库
});
/**********************************************************************************/


/************************** 商品管理 ***********************************************/
Route::prefix('goods')->middleware('checklogin')->group(function(){
	Route::get('list','admin\GoodsController@list');				// 商品列表
	Route::get('create','admin\GoodsController@create');			// 商品添加
	Route::post('save','admin\GoodsController@save');				// 商品添加入库
	Route::get('delete/{id}','admin\GoodsController@delete');		// 商品删除
	Route::get('edit/{id}','admin\GoodsController@edit');			// 商品修改
	Route::post('update/{id}','admin\GoodsController@update');		// 商品修改入库
});
/**********************************************************************************/




/************************** 前台登陆 ***********************************************/
	Route::get('login','index\LoginController@login');				// 登陆视图
	Route::post('do_login','index\LoginController@do_login');		// 登陆处理
	Route::get('register','index\LoginController@register');		// 注册视图
	Route::post('do_register','index\LoginController@do_register');	// 注册处理
	Route::post('checkemail','index\LoginController@checkemail');	// 验证邮箱(格式、是否存在)
	Route::post('send','index\LoginController@send');				// 发送邮件(验证码)
	Route::post('checkcode','index\LoginController@checkcode');		// 检测验证码(格式、是否正确)
/**********************************************************************************/


/************************** 前台管理 ***********************************************/
	Route::get('','index\IndexController@index');					// 主页视图

Route::prefix('index')->middleware('landing')->group(function(){
	Route::get('user','index\IndexController@user');				// 用户详情视图
	Route::get('info','index\IndexController@info');				// 用户信息修改视图
	Route::post('do_info','index\IndexController@do_info');			// 用户信息修改处理
	Route::get('logout','index\IndexController@logout');			// 退出登录

	Route::get('detail/{id}','index\IndexController@detail');		// 商品详情
	Route::get('add_car/{id}','index\IndexController@add_car');		// 添加商品到购物车
	Route::get('car','index\IndexController@car');					// 购物车视图
	Route::get('pay','index\IndexController@pay');					// 支付视图
});
/**********************************************************************************/


/************************************************ 微信测试 *****************************************************/

Route::prefix('wechat')->group(function(){
    Route::get('list','WechatController@get_user_list');                    // 用户列表
    Route::get('detail','WechatController@get_user_detail');                // 用户详情
    Route::get('login','WechatController@login');                           // 微信授权登陆
    Route::get('wechat_login','WechatController@wechat_login');             // 微信登陆
    Route::get('code','WechatController@code');                             // 接收用户code

    Route::get('curl_1','WechatController@curl_1');                         // curl_1测试
    Route::get('curl','WechatController@curl');                             // curl测试

    Route::get('sucai','WechatController@sucai');                           // 素材上传视图
    Route::post('upload_do','WechatController@upload_do');                  // 上传素材最终结果
    Route::get('curl_upload','WechatController@curl_upload');               // 上传素材处理

    Route::get('source','WechatController@wechat_source');                  // 微信素材管理页面
    Route::get('clear_api','WechatController@clear_api');                   // 调用频次清0

    Route::get('taglist','wechat\TagController@list');                      // 标签列表
    Route::get('addtag','wechat\TagController@create');                     // 添加标签
    Route::post('savetag','wechat\TagController@save');                     // 添加标签处理
    Route::get('edittag','wechat\TagController@edit');                      // 修改标签
    Route::post('updatetag','wechat\TagController@update');                 // 修改标签处理
    Route::get('deletetag/{id}','wechat\TagController@delete');             // 删除标签

    Route::get('fanslist','wechat\TagController@fans_openid_list');         // 标签下粉丝列表
    Route::get('userlist','wechat\TagController@get_user_list');            // 所有关注的用户列表
    Route::get('taguserlist','wechat\TagController@get_tag_user_list');     // 所有关注的用户列表
    Route::post('tag_openid','wechat\TagController@tag_openid');            // 为用户加标签
    Route::get('usertaglist','wechat\TagController@user_tag_list');         // 用户的标签列表

    Route::get('push_message','wechat\TagController@push_message');         // 根据标签群发消息视图
    Route::post('do_push_message','wechat\TagController@do_push_message');  // 根据标签群发消息处理

    Route::get('send_message','wechat\TagController@send_template_message');// 发送模板消息
});
/**************************************************************************************************************/

/************************************************ 用户推送消息 *************************************************/
Route::prefix('user')->group(function(){
    Route::post('send_message','wechat\TagController@send_message');        // 根据openid单用户发送消息
    Route::post('do_send_message','wechat\TagController@do_send_message');  // 根据openid单用户发送消息
});
/**************************************************************************************************************/

/************************************************ 用户推送消息 *************************************************/
Route::prefix('agent')->namespace('wechat')->group(function(){
    Route::get('list','AgentController@agent_list');                         // 获取关注用户视图
    Route::get('create_qrcode','AgentController@create_qrcode');             // 创建二维码
});
/**************************************************************************************************************/


/************************************************ 自定义菜单** *************************************************/
Route::prefix('menu')->namespace('wechat')->group(function(){
   Route::get('menu','MenuController@menu');                                 // 自定义菜单
   Route::get('create_menu','MenuController@create_menu');                   // 添加自定义菜单
    Route::post('save_menu','MenuController@save_menu');                      // 添加自定义菜单 处理
    Route::get('del_menu','MenuController@del_menu');                      // 删除自定义菜单
    Route::get('get_wechat_access_token','MenuController@get_wechat_access_token');  //获取token
});
/**************************************************************************************************************/

/**************************************JS-SDK*******************************************************************/
Route::prefix('sdk')->namespace('wechat')->group(function(){
    Route::get('sdk_list','JSSDKController@sdk_list');
    Route::get('get_wechat_ticket','JSSDKController@get_wechat_ticket');

});
/************************************XML数据解析 **************************************************************************/
Route::prefix('wechat')->group(function(){
    Route::post('event','wechat\EventController@event');//XML数据解析
});

/*******************************************月考测试1*******************************************************************/
Route::prefix('ceshi1')->group(function(){
    Route::get('wechat_login','ceshi\Ceshi1Controller@wechat_login');//微信授权登录
    Route::post('wechat_login_do','ceshi\Ceshi1Controller@wechat_login_do');//微信授权登录
    Route::get('code','ceshi\Ceshi1Controller@code');//微信授权登录
    Route::get('get_userinfo','ceshi\Ceshi1Controller@get_userinfo');//添加用户粉丝列表
    Route::get('userinfo','ceshi\Ceshi1Controller@userinfo');//微信用户列表
    Route::get('biaoqian','ceshi\Ceshi1Controller@biaoqian');//创建用户标签
    Route::get('tag_list','ceshi\Ceshi1Controller@tag_list');//展示用户标签
    Route::get('tag_del','ceshi\Ceshi1Controller@tag_del');//删除用户标签
    Route::get('tag_save','ceshi\Ceshi1Controller@tag_save');//添加用户标签
    Route::get('tag_word','ceshi\Ceshi1Controller@tag_word');//用户标签群发消息
    Route::post('tag_word_do','ceshi\Ceshi1Controller@tag_word_do');//用户标签群发消息处理
    Route::post('biaoqian_do','ceshi\Ceshi1Controller@biaoqian_do');//用户标签处理

});

/******************************************月考测试2******************************************************************/
Route::prefix('ceshi2')->group(function(){
    Route::get('create_menu','ceshi\Ceshi2Controller@create_menu');//自定义菜单视图页面
    Route::post('create_menu_do','ceshi\Ceshi2Controller@create_menu_do');//自定义菜单处理页面
    Route::post('event','ceshi\Ceshi2Controller@event');//关注取消关注事件
    Route::post('ceshi','ceshi\Ceshi2Controller@ceshi');//测试任务调度
    Route::post('ceshi2','ceshi\Ceshi2Controller@ceshi2');//测试任务调度

});

/******************************************月考三*****************************************************************************/
Route::prefix('ceshi3')->group(function(){
    Route::get('guanli','ceshi\Ceshi3Controller@guanli');//第三方登录页面
    Route::post('guanli_do','ceshi\Ceshi3Controller@guanli_do');//登录处理页面
    Route::get('code','ceshi\Ceshi3Controller@code');//获取code
    Route::get('manage','ceshi\Ceshi3Controller@manage');//课程管理页面
    Route::post('manage_do','ceshi\Ceshi3Controller@manage_do');//课程管理页面处理
    Route::get('index','ceshi\Ceshi3Controller@index');//课程展示页面
    Route::get('update','ceshi\Ceshi3Controller@update');//课程修改页面
    Route::post('update_do','ceshi\Ceshi3Controller@update_do');//课程修改页面处理
    Route::get('chakan','ceshi\Ceshi3Controller@chakan');//查看课程页面
});
