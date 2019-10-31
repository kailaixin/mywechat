@extends('layouts.child')

@section('title', '登录页面')

@section('content')
    <form style="margin: 6%" >
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">用户名</label>
            <input type="text" name="api_name" class="form-control" id="exampleInputEmail1" placeholder="请输入用户名">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">用户密码</label>
            <input type="password" name="api_pwd" class="form-control" id="exampleInputPassword1" placeholder="请输入密码">
        </div>

        <input type="button" class="btn btn-default" value="登录">

    <script src="/js/publiccookie.js"></script>
    <script>
        $('.btn-default').on('click',function(){
            var username = $('[name="api_name"]').val();
            var userpwd = $('[name="api_pwd"]').val();
            var url = 'http://www.1902.com/ceshi4/login_do';
               $.ajax({
                   url:url,
                   data:{username:username,userpwd:userpwd},
                   dataType:'json',
                   type:'post',
                   success:function (res) {
                       if(res.code==200){
                           alert(res.msg);
                           setCookie('token',res.token,7200);
                           location.href="http://www.1902.com/ceshi4/admin";
                       }else{
                           alert(res.msg);
                       }
                   }
               });



        });
    </script>
@endsection

