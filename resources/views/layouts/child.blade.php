<!-- 保存在 resources/views/child.blade.php 中 -->

@extends('layouts.index')

@section('title', '我的商城')

@section('content')
    <!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="{{asset('admin1/css/bootstrap.min.css?v=3.3.6')}}" rel="stylesheet">
    <link href="{{asset('admin1/css/font-awesome.css?v=4.4.0')}}" rel="stylesheet">

    <link href="{{asset('admin1/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('admin1/css/style.css?v=4.1.0')}}" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">h</h1>

        </div>
        <h3>欢迎使用 hAdmin</h3>

        <form  role="form" action="{{url('login/login_do')}}" method="post">
            @csrf
            <div class="form-group">
                <input type="email" class="form-control" placeholder="用户名" name="user_name" required="">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="密码" name="user_pwd" required="">
            </div>
            <div >
                <input type="text"  placeholder="验证码"  name="user_code" >
                <input type="button" id="code" value="发送微信验证码">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>


            <p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>
            </p>

            <div>
                <img src="{{asset('admin1/img/0.jpg')}}" alt="" align="center" height="200" width="200">
            </div>

        </form>
    </div>
</div>

<!-- 全局js -->
<script src="{{asset('admin1/js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('admin1/js/bootstrap.min.js?v=3.3.6')}}"></script>

</body>

</html>
<script src="{{asset('admin1/js/jquery.js')}}"></script>
<script>
    $('#code').click(function(){
        event.preventDefault();
        // alert(code);
        $.ajax({
            url:'{{url('login/code')}}',

            type:'POST',
            success:function(req){
                if(req.code==1){
                    alert(req.msg);
                }
            }
        })
    });
</script>


@endsection
