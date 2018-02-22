<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;


class UserController extends Controller
{
    // 个人设置页面
    public function setting(User $user)
    {
        $me = \Auth::user();
        return view('user/setting', compact('me'));
    }

    // 个人设置页面动作
    public function settingStore(Request $request, User $user)
    {
        $this->validate(request(), [
            'name' => 'min:3|max:20'
        ], [
            'name.min' => '名字不能少于3个字',
            'name.max' => '名字不能超过20个字',
        ]);

        $name = request('name');
        if ($name != $user->name) {
            if (\App\Models\User::where('name', $name)->count() > 0) {
                return back()->withErrors(array('message' => '用户已经被注册'));
            }
            $user->name = $name;
        }
        if ($request->file('avatar')) {

            $disk = QiniuStorage::disk('qiniu');
            $filename = $disk->put('header', $request->file('avatar'));
            $img_url = urldecode($disk->downloadUrl($filename, 'https')); //获取下载链接

            // 插入Image
            $image = new Image();
            $image->user_id = \Auth::id();
            $image->type = 'header';
            $image->url = $img_url;
            $image->status = '1';
            $image->extra_info = '';
            $image->save();

            $user->avatar = $img_url;
        }
        $user->save();
        return redirect("/user/{$user->id}/setting")->with('status', '修改信息成功');
    }

    // 个人中心页面
    public function show(User $user)
    {

        // 这个人信息， 关注，粉丝，文章 hasmany的关联
        $user = User::withCount(['stars', 'fans', 'posts'])->find($user->id);

        // 这个人的文章列表，最新的前十条
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();

        // 这个人关注的用户， 包含关注用户的 关注，粉丝，文章
        $stars = $user->stars;
        $susers = User::whereIn('id', $stars->pluck('star_id'))->withCount(['stars', 'fans', 'posts'])->get();

        // 这个人的粉丝用户， 包含粉丝用户的 关注，粉丝，文章
        $fans = $user->fans;
        $fusers = User::whereIn('id', $fans->pluck('fan_id'))->withCount(['stars', 'fans', 'posts'])->get();


        return view('user/show', compact('user', 'posts', 'susers', 'fusers'));
    }

    // 关注
    public function fan(User $user)
    {
        $me = \Auth::user();
        $me->doFan($user->id);
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    // 取消关注
    public function unfan(User $user)
    {
        $me = \Auth::user();
        $me->doUnFan($user->id);
        return [
            'error' => 0,
            'msg' => ''
        ];
    }


    // 更改密码界面
    public function forget(User $user)
    {
        return view('user/forget');
    }


    // 更改页面动作
    public function change(User $user)
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

        // 更改数据
        $password = bcrypt(request('password'));
        $user = User::where(['name' => $user->name, 'email' => $user->email])->first();
        $user->password = $password;
        $user->save();

        // 跳转登录页面
        return redirect("/login")->with('status', '修改密码成功');
    }

}
