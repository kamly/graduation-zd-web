<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/6
 * Time: 下午8:39
 */

namespace App\Admin\Controllers;


use App\Models\Post;

class PostController extends Controller
{
    // 文章管理模块首页
    public function index()
    {
        $posts = Post::withoutGlobalScope('avaiable')->where('status', '0')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin/posts/index', compact('posts'));
    }

    // 文章状态改变动作
    public function status(Post $post)
    {
        $this->validate(request(), [
            'status' => 'required|in:-1,1'
        ]);

        $post->status = request('status');
        $post->save();

        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}