<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2017/12/10
 * Time: 下午7:10
 */

namespace App\Admin\Controllers;


use App\Models\Image;
use App\Models\Topic;
use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;
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
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|min:4|max:30|unique:topics,name'
        ], [
            'name.min' => '名字不能少于4个字',
            'name.max' => '名字不能超过30个字',
            'name.unique' => '名字出现重复',
            'name.required' => '必须填写名字',
        ]);

        $is_repeat_name = Topic::where(['name' => request('name')])->first();
        if ($is_repeat_name) {
            return back()->withErrors(array('message' => '名字已经被注册'));
        }

        $name = request('name');
        $des = request('des');
        $topic = Topic::create(compact(['name', 'des']));

        if ($topic->id && $request->file('avatar')) {

            $disk = QiniuStorage::disk('qiniu');
            $filename = $disk->put('header', $request->file('avatar'));
            $img_url = urldecode($disk->downloadUrl($filename, 'https')); //获取下载链接

            // 插入Image
            $image = new Image();
            $image->user_id = \Auth::guard('admin')->id();
            $image->type = 'topic';
            $image->type_id = $topic->id;
            $image->url = $img_url;
            $image->status = '1';
            $image->extra_info = '';
            $image->save();
        }

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
        $image = Image::where(['type' => 'topic', 'type_id' => $topic->id])->first(); // 只选最新一张
        return view('admin/topic/update', compact('topic', 'image'));
    }


    // 修改动作
    public function update(Request $request, Topic $topic)
    {
        $this->validate(request(), [
            'name' => 'required|min:4|max:30',
            'des' => 'required|min:4|max:30'
        ], [
            'name.min' => '名字不能少于4个字',
            'name.max' => '名字不能超过30个字',
            'name.required' => '必须填写名字',
            'des.min' => '描述不能少于4个字',
            'des.max' => '描述不能超过30个字',
            'des.required' => '必须填写描述',
        ]);

        $is_repeat_name = Topic::where(['name' => request('name')])->first();
        if ($is_repeat_name && $is_repeat_name->id != $topic->id) {
            return back()->withErrors(array('message' => '名字已经被注册'));
        }

        $topic->name = request('name');
        $topic->des = request('des');
        $topic->save();

        if ($request->file('avatar')) {
            $disk = QiniuStorage::disk('qiniu');
            $filename = $disk->put('header', $request->file('avatar'));
            $img_url = urldecode($disk->downloadUrl($filename, 'https')); //获取下载链接

            // 判断之前有没有图片
            $image = (new Image)->where(['type' => 'topic', 'tpye_id' => $topic->id, 'status' => '1'])->first();
            if ($image) {
                $image->user_id = \Auth::guard('admin')->id();
                $image->url = $img_url;
                $image->save();
            } else {
                // 插入Image
                $image = new Image();
                $image->user_id = \Auth::guard('admin')->id();
                $image->type = 'topic';
                $image->type_id = $topic->id;
                $image->url = $img_url;
                $image->status = '1';
                $image->extra_info = '';
                $image->save();
            }
        }

        return redirect('admin/topics')->with('status', '专题修改成功');
    }
}