<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>课程展示页面</title>
</head>
<body>
<h3 align="center"><a href="{{url('ceshi3\manage')}}">添加课程</a></h3>
<form action="" align="center">
    <table border="1" align="center">
        <tr>
            <th>id</th>
            <th>第一节课</th>
            <th>第二节课</th>
            <th>第三节课</th>
            <th>第四节课</th>
            <th>操作</th>
        </tr>
        @foreach($data as $v)
        <tr>
            <td>{{$v->ke_id}}</td>
            <td>{{$v->php}}</td>
            <td>{{$v->yuwen}}</td>
            <td>{{$v->shuxue}}</td>
            <td>{{$v->yingyu}}</td>
            <td>
                <a href="{{url('ceshi3\update')}}?ke_id={{$v->ke_id}}">修改</a>
            </td>
        </tr>
            @endforeach
    </table>
</form>
</body>
</html>
