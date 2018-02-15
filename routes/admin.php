<?php

// 管理后台

Route::group(['prefix' => 'admin'], function () {
    // 登录展示页面
    Route::get('/login', '\App\Admin\Controllers\LoginController@index');
    // 登录展示操作
    Route::post('/login', '\App\Admin\Controllers\LoginController@login');

    Route::group(['middleware' => 'auth:admin'], function () {

        // 登出
        Route::get('/logout', '\App\Admin\Controllers\LoginController@logout');

        // 首页
        Route::get('/home', '\App\Admin\Controllers\HomeController@index');


        Route::group(['middleware' => 'can:system'], function () {
            /*
             * 管理人员模块
             */
            // 管理员人员列表页面
            Route::get('/users', '\App\Admin\Controllers\UserController@index');
            // 管理员人员创建页面
            Route::get('/users/create', '\App\Admin\Controllers\UserController@create');
            // 管理员人员创建页面动作
            Route::post('/users/store', '\App\Admin\Controllers\UserController@store');
            // 管理员人员修改页面
            Route::get('/users/{user}/edit', '\App\Admin\Controllers\UserController@edit');
            // 管理员人员修改页面动作
            Route::post('/users/{user}/edit', '\App\Admin\Controllers\UserController@update');
            // 管理员人员删除
            Route::get('/users/{user}/delete', '\App\Admin\Controllers\UserController@delete');
            // 管理员角色
            Route::get('/users/{user}/role', '\App\Admin\Controllers\UserController@role');
            // 管理员角色保存
            Route::post('/users/{user}/role', '\App\Admin\Controllers\UserController@storeRole');


            /*
             * 角色模块
             */
            // 角色列表页面
            Route::get('/roles', '\App\Admin\Controllers\RoleController@index');
            // 角色创建页面
            Route::get('/roles/create', '\App\Admin\Controllers\RoleController@create');
            // 角色创建动作
            Route::post('/roles/store', '\App\Admin\Controllers\RoleController@store');
            // 角色修改页面
            Route::get('/roles/{role}/edit', '\App\Admin\Controllers\RoleController@edit');
            // 角色修改页面动作
            Route::post('/roles/{role}/edit', '\App\Admin\Controllers\RoleController@update');
            // 角色删除
            Route::get('/roles/{role}/delete', '\App\Admin\Controllers\RoleController@delete');
            // 角色权限列表
            Route::get('/roles/{role}/permission', '\App\Admin\Controllers\RoleController@permission');
            // 角色权限保存
            Route::post('/roles/{role}/permission', '\App\Admin\Controllers\RoleController@storePermission');


            /*
            * 权限模块
            */
            // 权限列表页面
            Route::get('/permissions', '\App\Admin\Controllers\PermissionController@index');
            // 权限创建页面
            Route::get('/permissions/create', '\App\Admin\Controllers\PermissionController@create');
            // 权限创建动作
            Route::post('/permissions/store', '\App\Admin\Controllers\PermissionController@store');
            // 权限修改页面
            Route::get('/permissions/{permission}/edit', '\App\Admin\Controllers\PermissionController@edit');
            // 权限修改页面动作
            Route::post('/permissions/{permission}/edit', '\App\Admin\Controllers\PermissionController@update');

        });

        Route::group(['middleware' => 'can:post'], function () {
            /*
             * 文章审核模块
             */
            // 文章管理模块首页
            Route::get('/posts', '\App\Admin\Controllers\PostController@index');
            // 文章状态改变动作
            Route::post('/posts/{post}/status', '\App\Admin\Controllers\PostController@status');
        });


        Route::group(['middleware' => 'can:topic'], function () {
            /*
             * 专题模块
             */
            // 专题列表
            Route::get('/topics', '\App\Admin\Controllers\TopicController@index');
            // 专题创建页面
            Route::get('/topics/create', '\App\Admin\Controllers\TopicController@create');
            // 专题创建页面动作
            Route::post('/topics/create', '\App\Admin\Controllers\TopicController@store');
            // 专题删除
            Route::delete('/topics/{topic}', '\App\Admin\Controllers\TopicController@destroy');
            // 专题修改页面
            Route::get('/topics/{topic}/edit', '\App\Admin\Controllers\TopicController@edit');
            // 专题修改页面动作
            Route::post('/topics/{topic}/edit', '\App\Admin\Controllers\TopicController@update');
        });

        Route::group(['middleware' => 'can:notice'], function () {
            /*
             * 通知模块
             */
            Route::get('/notices', '\App\Admin\Controllers\NoticeController@index');
            // 专题创建页面
            Route::get('/notices/create', '\App\Admin\Controllers\NoticeController@create');
            // 专题创建页面动作
            Route::post('/notices/create', '\App\Admin\Controllers\NoticeController@store');
            // 专题删除
            Route::get('/notices/{notice}/delete', '\App\Admin\Controllers\NoticeController@destroy');
            // 专题修改页面
            Route::get('/notices/{notice}/edit', '\App\Admin\Controllers\NoticeController@edit');
            // 专题修改页面动作
            Route::post('/notices/{notice}/edit', '\App\Admin\Controllers\NoticeController@update');

        });


    });

});