@extends("layout.main")

@section("content")

    <div class="alert alert-success" role="alert">
        下面是搜索"{{$query}}"出现的文章和评论，共{{$num}}条
    </div>

    <div class="col-sm-8 blog-main">
        @foreach($postComments as $postComment)
            @if ($postComment['class_name'] == 'post')
                <div class="blog-post" style="border-bottom: 1px solid #dff0d8;">
                    <h2 class="blog-post-title"><a
                                href="/posts/{{$postComment['post_id']}}">{{$postComment['title']}}</a></h2>
                    <p class="blog-post-meta">{{$postComment['created_at']}} by <a
                                href="/user/{{$postComment['user']['id']}}">{{$postComment['user']['name']}}</a></p>
                    <p>{!! str_limit($postComment['content'], 100, '...') !!}</p>
                </div>
            @else
                <div class="blog-post" style="border-bottom: 1px solid #dff0d8;">
                    <h2 class="blog-post-title"><a
                                href="/posts/{{$postComment['post_id']}}">{{$postComment['post']['title']}}</a></h2>
                    <p><a href="/user/{{$postComment['user']['id']}}">{{$postComment['user']['name']}}</a> 评论：{!!
                    str_limit
                    ($postComment['content'], 100, '...') !!} </p>
                </div>
            @endif

        @endforeach
    </div><!-- /.blog-main -->
@endsection("content")
