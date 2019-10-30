@extends('layouts.child')

@section('title', '注册页面')

@section('content')
    <form style="margin: 6%">
        <div class="form-group">
            <label for="exampleInputEmail1">用户名</label>
            <input type="email" name="username" class="form-control" id="exampleInputEmail1" placeholder="请输入要注册的用户名">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">用户密码</label>
            <input type="password" name="userpwd" class="form-control" id="exampleInputPassword1" placeholder="请输入注册密码">
        </div>

        <input type="button" class="btn btn-default" value="注册">
    </form>
    <script>
        // alert($);
        $('[type="button"]').on('click',function(){
            var username = $('[name="username"]');
            var userpwd = $('[name="userpwd"]');
            var url = 'http://www.1902.com/api/test1/reg_do';
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

