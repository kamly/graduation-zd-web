<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    // 用户注册界面
    public function index()
    {
        return view('register/index');
    }

    // 用户注册界面操作
    public function register()
    {
        // 验证
        $this->validate(request(), [
            'name' => 'required|min:3|max:20|unique:users,name',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:5|max:16|confirmed'
        ], [
            'name.min' => '名字不能少于3个字',
            'name.max' => '名字不能超过20个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
            'email.email' => '必须填写邮箱格式',
            'email.required' => '必须填写邮箱',
            'email.unique' => '邮箱出现重复',
            'password.required' => '必须填写密码',
            'password.min' => '密码不能少于5个字',
            'password.max' => '密码不能超过16个字',
            'password.confirmed' => '两次密码不一致',
        ]);

        // 逻辑
        $name = request('name');
        $email = request('email');
        $password = sha1(config('myConfig.salt') . sha1(config('myConfig.salt') . request('password')));
        $avatar = 'https://images.charmingkamly.cn/system/default-header.png'; // 默认头像
        $note = '';

        $user = User::create(compact(['name', 'email', 'password', 'avatar', 'note']));
        // 返回
        return redirect('/login')->with('status', '注册成功');
    }
}
