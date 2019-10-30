@extends('layouts.child')

@section('title', '登录页面')

@section('content')
    <form style="margin: 6%">
        <div class="form-group">
            <label for="exampleInputEmail1">用户名</label>
            <input type="email" name="api_name" class="form-control" id="exampleInputEmail1" placeholder="请输入要注册的用户名">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">用户密码</label>
            <input type="password" name="api_pwd" class="form-control" id="exampleInputPassword1" placeholder="请输入注册密码">
        </div>

        <input type="button" class="btn btn-default" value="登录">
    </form>
    <script>
        // alert($);
        $('[type="button"]').on('click',function(){
            var username = $('[name="api_name"]');
            var userpwd = $('[name="api_pwd"]');
            var url = 'http://www.1902.com/api/test1/login_do';
            $.ajax({
                url:url,
                data:{name:username,pwd:userpwd},
                dataType:'json',
                type:'post',
                success:function (res) {
                    console.log(res);
                    return false;
                },

            });

        });
    </script>
@endsection

