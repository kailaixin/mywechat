@extends('layouts.child')

@section('title', '后台系统')

@section('content')
    <form style="margin: 6%">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">appID:{{$userData['appid']}}</label>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">appsecret:{{$userData['appsecret']}}</label>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">js接口安全域名修改</label>
            <input type="text" name="safe_url" class="form-control" id="exampleInputEmail1" placeholder="请输入接口安全域名">
        </div>
        <input type="submit" class="btn btn-default" value="修改">
        <script>
           $(function(){//页面加载事件
               var safe_url = $('[name="safe_url"]').val();
             var url = 'http://www.1902.com/ceshi4/admin_do';
               $.ajax({
                  url:url,
                  data:{safe_url:safe_url},
                  dataType:'json',
                  success:function (res) {
                      console.log(res);
                  }
           })
           })


        </script>
@endsection

