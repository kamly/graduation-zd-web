<div class="blog-masthead">
    <div class="container">
        <ul class="nav navbar-nav navbar-left">
            <li>
                <a class="blog-nav-item " href="/posts">首页</a>
            </li>
            <li>
                <a class="blog-nav-item" href="/posts/create">写文章</a>
            </li>
            <li>
                <a class="blog-nav-item" href="/notices">通知</a>
            </li>
            <li class="media-display" style="margin-left: 10px;">
                <input id="search-text" type="text" value="" class="form-control"
                       style="margin-top:10px" placeholder="搜索">
            </li>
            <li class="media-display">
                <button class="btn btn-default" style="margin-top:10px" type="submit" id="search">Go!</button>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right media-display" style="line-height: 29px;">
            <li class="dropdown">
                <div>
                    <img src="{{ \Auth::user()->avatar }}"
                         alt="" class="img-rounded" style="border-radius:100%; height: 40px;width: 40px;">
                    <a href="#" class="blog-nav-item dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-haspopup="true" aria-expanded="false">{{ \Auth::user()->name }}<span
                                class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/user/{{ \Auth::user()->id }}">我的主页</a></li>
                        <li><a href="/user/{{ \Auth::user()->id }}/setting">个人设置</a></li>
                        <li><a href="/user/{{ \Auth::user()->id }}/forget">重置密码</a></li>
                        <li><a href="/logout">登出</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>