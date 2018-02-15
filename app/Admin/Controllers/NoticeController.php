<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/10
 * Time: 下午7:10
 */

namespace App\Admin\Controllers;


use App\Models\Notice;
use Illuminate\Auth\Access\Gate;

class NoticeController extends Controller
{

    // 通知列表
    public function index()
    {
        $notices = Notice::all();
        return view('admin/notice/index', compact('notices'));
    }


    // 通知创建页面
    public function create()
    {
        return view('admin/notice/add');
    }

    // 通知创建动作
    public function store()
    {
        $this->validate(request(), [
            'title' => 'required|min:4|max:50',
            'content' => 'required'
        ], [
            'title.min' => '名字不能少于4个字',
            'title.max' => '名字不能超过50个字',
            'title.required' => '必须填写名字',
            'content.required' => '必须填写内容',
        ]);

        $notice = Notice::create(request(['title', 'content']));

        // 分发任务
        dispatch(new \App\Jobs\SendNoticeMessage($notice));

        return redirect('admin/notices');
    }

    // 通知更改
    public function edit(Notice $notice)
    {
        return view('admin/notice/update', compact('notice'));
    }

    // 通知更改动作
    public function update(Notice $notice)
    {
        $this->validate(request(), [
            'title' => 'required|min:4|max:50',
            'content' => 'required'
        ], [
            'title.min' => '名字不能少于4个字',
            'title.max' => '名字不能超过50个字',
            'title.required' => '必须填写名字',
            'content.required' => '必须填写内容',
        ]);

        $notice->title = request('title');
        $notice->content = request('content');
        $notice->save();

        return redirect('admin/notices')->with('status', '通知修改成功');
    }

    // 通知删除
    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect('/admin/notices')->with('status', '通知删除成功');
    }
}