@extends("layout.main")

@section("content")
    <div class="col-sm-8 blog-main">
        @include('layout.success')
        <form class="form-signin" method="POST" action="/user/{{\Auth::id()}}/forget">
            {{csrf_field()}}
            <h2 class="form-signin-heading">重置密码</h2>
            <div style="margin: 10px auto;">
                <label for="inputEmail" class="sr-only">密码</label>
                <input type="password" name="password" id="inputEmail" class="form-control" placeholder="填写密码" required
                       autofocus>
            </div>
            <div style="margin: 10px auto;">
                <label for="inputEmail" class="sr-only">重复密码</label>
                <input type="password" name="password_confirmation" id="inputEmail" class="form-control"
                       placeholder="填写重复密码" required>
            </div>
            @include('layout.error')
            <button class="btn btn-lg btn-primary btn-block" type="submit">确认</button>
        </form>
    </div>
@endsection("content")