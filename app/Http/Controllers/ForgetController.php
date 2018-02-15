<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/13
 * Time: 下午12:49
 */

namespace App\Http\Controllers;


use App\Models\PasswordReset;
use App\Models\User;

use Illuminate\Support\Facades\Redis;
use Mail;

class ForgetController extends Controller
{
    // 忘记密码页面
    public function index()
    {
        return view('forget/index');
    }

    // 忘记密码动作
    public function sendEmail()
    {
        // 获取数据
        $this->validate(request(), [
            'email' => 'required|email'
        ], [
            'email.required' => '必须填写邮箱',
            'email.email' => '必须填写邮箱格式',
        ]);

        // 获取参数
        $email = request('email');

        // 检查是不是之前就有数据 （小于10min）
        $redis_result = Redis::get("forget_{$email}");
        if ($redis_result) {
            return redirect("/forget")->with('status', '已经发送完邮件，请稍后重试');
        }

        // 获取数据，生成token
        $user = User::where('email', $email)->first();
        $message = $user->name . '|' . $user->email . '|' . config('myConfig.token_base') . '|' . time(); // 生成token 名字+邮箱+盐+时间
        $token = message_encrypt($message);

        // token 唯一
        $non_unique = PasswordReset::where('token', $token)->exists();
        if ($non_unique) {
            return redirect("/forget")->with('status', '生成验证码失败，请稍后重试');
        }

        // 发送邮件
        $urlencodeToken = urlencode($token);
        $link = url("/forget/change?token={$urlencodeToken}");
        Mail::send('emails.forget', ['name' => $user->name, 'link' => $link], function ($message) use ($user) {
            $message->to($user->email)->subject('重置密码邮件');
        });

        PasswordReset::updateOrCreate(['name' => $user->name, 'email' => $user->email], ['name' => $user->name, 'email' => $user->email, 'token' => $token]);

        // 记录redis
        Redis::set("forget_{$email}", $token, 'EX', 600);

        return redirect("/forget")->with('status', '邮件发送成功');
    }


    // 验证页面
    public function checkToken()
    {
        $token = request('token'); // 自动urldecode
        if (empty($token)) {  // 无token
            return redirect("/forget")->with('status', '非法链接');
        }

        // 解密  名字+邮箱+盐+时间
        $message = explode('|', message_decrypt($token));

        // 检查第几次点击
        $redis_result = Redis::get("forget_{$message[1]}");
        if (!$redis_result) {
            return redirect("/forget")->with('status', '链接失效---已经被使用');
        }

        // 删除token
        Redis::del("forget_{$message[1]}");

        // 检查盐
        if ($message[2] != config('myConfig.token_base')) {
            return view('forget')->with('status', '链接失效---盐值错误');
        }

        // 检查时间
        $min = floor((time() - $message[3]) % 86400 / 60);
        if ($min > 10) {
            return redirect("/forget")->with('status', '链接失效---时间超时');
        }

        // 检查数据（token,email,name）
        $passwordReset = PasswordReset::where(['name' => $message[0], 'email' => $message[1], 'token' => $token])->exists();
        if (!$passwordReset) {
            return redirect("/forget")->with('status', '链接失效---匹配失败');
        }

        // 渲染页面
        $token = urlencode($token);
        return view("forget/change", compact('token'));
    }

    // 验证页面
    public function changePassword()
    {

        // 检查盐
        $this->validate(request(), [
            'password' => 'required|min:5|max:16|confirmed'
        ], [
            'password.required' => '必须填写密码',
            'password.min' => '密码不能少于5个字',
            'password.max' => '密码不能超过16个字',
            'password.confirmed' => '两次密码不一致',
        ]);

        $password = sha1(config('myConfig.salt') . sha1(config('myConfig.salt') . request('password')));
        $token = urldecode(request('token')); // 从form里面提取不会进行转义

        // 解密  名字+邮箱+盐+时间
        $message = explode('|', message_decrypt($token));

        // 检查时间
        $min = floor((time() - $message[3]) % 86400 / 60);
        if ($min > 100) {
            return redirect("/forget")->with('status', '链接失效---时间超时');
        }

        // 检查数据（token,email,name）
        $passwordReset = PasswordReset::where(['name' => $message[0], 'email' => $message[1], 'token' => $token])->exists();
        if (!$passwordReset) {
            return redirect("/forget")->with('status', '链接失效---匹配失败');
        }

        // 更改数据
        $user = User::where(['name' => $message[0], 'email' => $message[1]])->first();
        $user->password = $password;
        $user->save();

        // 删除数据
        PasswordReset::where(['name' => $message[0], 'email' => $message[1]])->delete();

        // 跳转登录页面
        return redirect("/login")->with('status', '修改密码成功');
    }
}