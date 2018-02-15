<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/3
 * Time: 下午8:44
 */

namespace App\Admin\Controllers;


class LoginController extends Controller
{
    // 用户登录页面
    public function index()
    {
        return view('admin/login/index');
    }

    // 用户登录页面动作
    public function login()
    {
        // 校验
        $this->validate(request(), [
            'name' => 'required|min:4|max:10',
            'password' => 'required|min:4|max:16'
        ]);

        // 逻辑
        $user = request(['name', 'password']);
        if (\Auth::guard('admin')->attempt($user)) {
            return redirect('/admin/home');
        }

        // 渲染
        return \Redirect::back()->withErrors('用户名密码不匹配');
    }

    // 用户登出
    public function logout()
    {
        \Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}