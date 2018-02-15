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

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::get('/test', function () {
//    return 'hello kamly';
//});


// a2c.charmingkamly.cn/login


Route::get('/', function () {
    return redirect('/login');
});

/*
 * 用户模块
 */
// 用户注册界面
Route::get('/register', '\App\Http\Controllers\RegisterController@index');
// 用户注册界面动作
Route::post('/register', '\App\Http\Controllers\RegisterController@register');
// 用户登录页面
Route::get('/login', '\App\Http\Controllers\LoginController@index')->name('login');
// 用户登录页面动作
Route::post('/login', '\App\Http\Controllers\LoginController@login');

/*
 * 忘记密码模块
 */
// 忘记密码页面
Route::get('/forget', '\App\Http\Controllers\ForgetController@index');
// 忘记密码动作
Route::post('/forget', '\App\Http\Controllers\ForgetController@sendEmail');
// 验证页面
Route::get('/forget/change', '\App\Http\Controllers\ForgetController@checkToken');
// 验证页面
Route::post('/forget/change', '\App\Http\Controllers\ForgetController@changePassword');


Route::group(['middleware' => 'auth:web'], function () {

    // 用户登出
    Route::get('/logout', '\App\Http\Controllers\LoginController@logout');

    /*
     * 搜索模块
     */
    // 搜索
    Route::get('/posts/search', '\App\Http\Controllers\PostController@search');

    /*
     * 文章模块
     */
    // 文章列表页
    Route::get('/posts', '\App\Http\Controllers\PostController@index');
    // 创建文章页
    Route::get('/posts/create', '\App\Http\Controllers\PostController@create');
    // 创建文章动作
    Route::post('/posts', '\App\Http\Controllers\PostController@store');
    // 文章详情页
    Route::get('/posts/{post}', '\App\Http\Controllers\PostController@show');
    // 编辑文章页
    Route::get('/posts/{post}/edit', '\App\Http\Controllers\PostController@edit');
    // 编辑文章动作
    Route::put('/posts/{post}', '\App\Http\Controllers\PostController@update');
    // 删除文章
    Route::get('/posts/{post}/delete', '\App\Http\Controllers\PostController@delete');
    // 图片上传
    Route::post('/posts/image/upload', '\App\Http\Controllers\PostController@imageUpload');
    // 提交评论
    Route::post('/posts/{post}/comment', '\App\Http\Controllers\PostController@comment');
    // 点赞
    Route::get('/posts/{post}/zan', '\App\Http\Controllers\PostController@zan');
    // 取消赞
    Route::get('/posts/{post}/unzan', '\App\Http\Controllers\PostController@unzan');


    /*
     * 个人模块
     */
    // 个人中心
    Route::get('/user/{user}', '\App\Http\Controllers\UserController@show');
    // 关注
    Route::post('/user/{user}/fan', '\App\Http\Controllers\UserController@fan');
    // 取消关注
    Route::post('/user/{user}/unfan', '\App\Http\Controllers\UserController@unfan');
    // 个人设置页面
    Route::get('/user/{user}/setting', '\App\Http\Controllers\UserController@setting');
    // 个人设置页面动作
    Route::post('/user/{user}/setting', '\App\Http\Controllers\UserController@settingStore');
    // 个人重置密码页面
    Route::get('/user/{user}/forget', '\App\Http\Controllers\UserController@forget');
    // 个人重置密码动作
    Route::post('/user/{user}/forget', '\App\Http\Controllers\UserController@change');


    /*
     * 专题模块
     */
    // 专题详情页
    Route::get('/topic/{topic}', '\App\Http\Controllers\TopicController@show');
    // 投稿
    Route::post('/topic/{topic}/submit', '\App\Http\Controllers\TopicController@submit');


    /*
     * 通知模块
     */
    // 通知
    Route::get('/notices', '\App\Http\Controllers\NoticeController@index');


});


include_once('admin.php');





