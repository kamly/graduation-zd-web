@extends("layout.main")

@section("content")
    <div class="col-sm-8 blog-main">
        @include('layout.success')
        <div>
            <div id="carousel-example" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    @foreach($images as $image)
                        @if($loop->first)
                            <li data-target="#carousel-example" data-slide-to="{{$loop->index}}" class="active"></li>
                        @else
                            <li data-target="#carousel-example" data-slide-to="{{$loop->index}}"></li>
                        @endif
                    @endforeach
                </ol><!-- Wrapper for slides -->
                <div class="carousel-inner">
                    @foreach($images as $image)
                        @if($loop->first)
                            <div class="item active">
                                <img src="{{$image->url}}" alt="..." style="height: 300px;"/>
                            </div>
                        @else
                            <div class="item">
                                <img src="{{$image->url}}" alt="..." style="height: 300px;"/>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span></a>
                <a class="right carousel-control" href="#carousel-example" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
        </div>
        <div style="height: 20px;">
        </div>
        <div>

            @foreach($posts as $post)
                <div class="blog-post">
                    <h2 class="blog-post-title"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h2>
                    <p class="blog-post-meta">{{$post->created_at->toFormattedDateString()}} by <a
                                href="/user/{{$post->user->id}}">{{$post->user->name}}</a></p>
                    <p>{!! str_limit($post->content, 100, '...') !!}</p>
                    <p class="blog-post-meta">赞 {{$post->zans_count}} | 评论 {{$post->comments_count}}</p>
                </div>
            @endforeach

            {{$posts->links()}}

        </div><!-- /.blog-main -->
    </div>
@endsection("content")
