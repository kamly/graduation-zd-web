<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // 用户登录页面
    public function index()
    {
        return view('login/index');
    }

    // 用户登录页面动作
    public function login()
    {
        // 校验
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:16',
            'is_remember' => 'integer'
        ], [
            'email.required' => '必须填写邮箱',
            'email.email' => '必须填写邮箱格式',
            'password.required' => '必须填写密码',
            'password.min' => '密码不能少于5个字',
            'password.max' => '密码不能超过16个字',
            'is_remember' => '记住登录必须是数字'
        ]);

        // 逻辑
        $user = request(['email', 'password']);
        $is_remember = boolval(request('is_remember'));
        if (\Auth::attempt($user, $is_remember)) {
            return redirect('/posts');
        }

        // 渲染
        return \Redirect::back()->withErrors('邮箱密码不匹配');
    }

    // 用户登出
    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }
}
