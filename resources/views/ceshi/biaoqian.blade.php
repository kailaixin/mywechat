<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加标签</title>
</head>
<body>
        <h1 align="center"><a href="{{url('ceshi1/tag_list')}}">标签列表</a> </h1>
<form action="{{url('ceshi1\biaoqian_do')}}" method="post" align="center">
    @csrf
            添加标签: <input type="text" name="name">
                    <input type="submit" value="添加">

</form>
</body>
</html>
