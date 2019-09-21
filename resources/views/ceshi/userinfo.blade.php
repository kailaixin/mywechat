<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>粉丝列表</title>
</head>
<body>
        <table border="1">
            <tr>
                <th>编号</th>
                <th>姓名</th>
                <th>openid</th>
                <th>头像</th>
                <th>关注时间</th>
                <th>性别</th>
                <th>操作</th>
            </tr>
{{--            @php  print_r($data);  @endphp--}}
            @foreach($data as $v)
            <tr>
                <td>{{$v['id']}}</td>
                <td>{{$v['nickname']}}</td>
                <td>{{$v['openid']}}</td>
                <td><img src="{{$v['headimgurl']}}" width="50" height="50" alt=""></td>
                <td>{{date("Y-m-d H:i:s",$v['subscribe_time'])}}</td>
                <td> @if($v['sex']==1)男 @else 女 @endif </td>
                <td>
                    <a href="{{url('ceshi1/tag_save')}}?tag_id={{$tag_id}}&openid={{$v['openid']}}">添加标签</a>
                </td>

            </tr>
            @endforeach
        </table>
</body>
</html>
