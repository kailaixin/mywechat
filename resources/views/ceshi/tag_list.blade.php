<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>标签列表</title>
</head>
<body>
                <h1 align="center"><a href="{{url('ceshi/biaoqian')}}">添加标签</a></h1>
<form action="" align="center">
        <table border="1" align="center">
            <tr>
                <th>标签id</th>
                <th>分组名称</th>
                <th>分组人数</th>
                <th>操作</th>
            </tr>
            @foreach($tag_info as $v)
                <tr>
                    <td>{{$v['id']}}</td>
                    <td>{{$v['name']}}</td>
                    <td>{{$v['count']}}</td>
                    <td>
                        <a href="{{url('ceshi1/userinfo')}}?tag_id={{$v['id']}}">添加标签</a>|
                        <a href="{{url('ceshi1/tag_del')}}?tag_id={{$v['id']}}">删除标签</a>|
                        <a href="{{url('ceshi1/tag_word')}}?tag_id={{$v['id']}}">群发消息</a>
                    </td>
                </tr>
                @endforeach
        </table>
</form>
</body>
</html>
