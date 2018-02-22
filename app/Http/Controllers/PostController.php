<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Zan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Michelf\Markdown;
use zgldh\QiniuStorage\QiniuStorage;

class PostController extends Controller
{
    // 文章列表页
    public function index()
    {
        // 随机抽取5张图片
        $images = Image::where(['type' => 'post'])->orderBy(DB::raw('RAND()'))->take(5)->get();

        $posts = Post::orderBy('created_at', 'desc')->withCount(['comments', 'zans'])->paginate(10);
        return view('post/index', compact('posts', 'images'));
    }


    // 文章详情页
    public function show(Post $post)
    {
        $post->load('comments'); // 预加载，这样view层不会再做查询操作
        $post->content = Markdown::defaultTransform($post->content);
        return view('post/show', compact('post'));
    }

    // 创建文章
    public function create()
    {
        return view('post/create');
    }

    // 创建文章动作
    public function store()
    {
        // 校验数据格式
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:2',
            'content' => 'required|string|min:10'
        ], [
            'title.required' => '必须填写标题',
            'title.min' => '文章标题不能少于2个字',
            'title.max' => '文章标题不能超过100个字',
            'title.string' => '文章标题必须是字符串',
            'content.required' => '必须填写文章内容',
            'content.max' => '文章内容不能少于10个字',
            'content.string' => '文章内容必须是字符串',
        ]);

        $user_id = \Auth::id();
        $params = array_merge(request(['title', 'content']), compact('user_id'));
        Post::create($params);

        return redirect('/posts/create')->with('status', '文章创建成功');
    }

    // 编辑文章页
    public function edit(Post $post)
    {
        return view('post/edit', compact('post'));
    }

    // 编辑文章动作
    public function update(Post $post)
    {
        // 验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10'
        ], [
            'title.required' => '必须填写标题',
            'title.min' => '文章标题不能少于2个字',
            'title.max' => '文章标题不能超过100个字',
            'title.string' => '文章标题必须是字符串',
            'content.required' => '必须填写文章内容',
            'content.max' => '文章内容不能少于10个字',
            'content.string' => '文章内容必须是字符串',
        ]);

        $this->authorize('update', $post);

        // 逻辑
        $post->title = request('title');
        $post->content = request('content');
        $post->save();

        // 渲染
        return redirect("/posts/{$post->id}/edit")->with('status', '文章修改成功');
    }

    // 删除文章
    public function delete(Post $post)
    {
        // 用户权限验证
        $this->authorize('delete', $post);

        $post->delete();
        return redirect('/posts')->with('status', '文章删除成功');
    }

    // 上传图片
    public function imageUpload(Request $request)
    {

        $disk = QiniuStorage::disk('qiniu');
        $filename = $disk->put('post',$request->file('wangEditorH5File'));
        $img_url = urldecode($disk->downloadUrl($filename, 'https')); //获取下载链接

        // 插入Image
        $image = new Image();
        $image->user_id = \Auth::id();
        $image->type = 'post';
        $image->url = $img_url;
        $image->status = '1';
        $image->extra_info = '';
        $image->save();

        return $img_url;
    }

    // 提交评论
    public function comment(Post $post)
    {
        // 验证
        $this->validate(request(), [
            'content' => 'required|min:3'
        ], [
            'content.min' => '评论内容不能少于3个字',
            'content.required' => '必须填写评论内容'
        ]);

        // 逻辑
        $comment = new Comment();
        $comment->user_id = \Auth::id();
        $comment->content = request('content');
        $post->comments()->save($comment);

        // 返回
        return redirect("/posts/{$post->id}")->with('status', '评论发表成功');
    }

    // 赞
    public function zan(Post $post)
    {
        $param = [
            'user_id' => \Auth::id(),
            'post_id' => $post->id,
        ];
        Zan::firstOrCreate($param);
        return redirect("/posts/{$post->id}")->with('status', '点赞成功');
    }

    // 取消赞
    public function unzan(Post $post)
    {
        $post->zan(\Auth::id())->delete();
        return redirect("/posts/{$post->id}")->with('status', '取消赞成功');
    }

    // 搜索结果页面
    public function search()
    {
        $this->validate(request(), [
            'query' => 'required'
        ], [
            'query.required' => '必须填写查询内容'
        ]);

        $query = request('query');
        $posts = Post::search($query)->paginate(10);

        return view('post/search', compact('posts', 'query'));
    }
}
