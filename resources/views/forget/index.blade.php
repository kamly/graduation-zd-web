<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>重置密码</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://v3.bootcss.com/examples/signin/signin.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">
    @include('layout.success')
    <form class="form-signin" method="POST" action="/forget">
        {{csrf_field()}}
        <h2 class="form-signin-heading">重置密码</h2>
        <div style="margin: 10px auto;">
            <label for="inputEmail" class="sr-only">邮箱</label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="填写邮箱" required
                   autofocus>
        </div>
        @include('layout.error')
        <button class="btn btn-lg btn-primary btn-block" type="submit">发送邮件</button>
        <a href="/login" class="btn btn-sm btn-default btn-block" type="submit">去登录>></a>
    </form>

</div> <!-- /container -->

</body>
</html>
