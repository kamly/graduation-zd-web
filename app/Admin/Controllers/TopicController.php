<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/10
 * Time: 下午7:10
 */

namespace App\Admin\Controllers;


use App\Models\Topic;
use Illuminate\Auth\Access\Gate;

class TopicController extends Controller
{
    // 列表
    public function index()
    {
        $topics = Topic::all();
        return view('admin/topic/index', compact('topics'));
    }

    // 创建页面
    public function create()
    {
        return view('admin/topic/add');
    }

    // 创建动作
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:4|max:30|unique:topics,name'
        ], [
            'name.min' => '名字不能少于4个字',
            'name.max' => '名字不能超过30个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
        ]);

        Topic::create(['name' => request('name')]);
        return redirect('admin/topics')->with('status', '专题添加成功');
    }

    // 删除
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    // 修改页面
    public function edit(Topic $topic)
    {
        return view('admin/topic/update', compact('topic'));
    }


    // 修改动作
    public function update(Topic $topic)
    {
        $this->validate(request(), [
            'name' => 'required|min:4|max:30'
        ], [
            'name.min' => '名字不能少于4个字',
            'name.max' => '名字不能超过30个字',
            'name.required' => '必须填写名字',
        ]);

        $is_repeat_name = Topic::where(['name' => request('name')])->first();
        if ($is_repeat_name && $is_repeat_name->id != $topic->id) {
            return back()->withErrors(array('message' => '名字已经被注册'));
        }

        $topic->name = request('name');
        $topic->save();

        return redirect('admin/topics')->with('status', '专题修改成功');
    }
}