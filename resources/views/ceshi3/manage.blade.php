<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>课程管理页面</title>
</head>
<body>
<h3 align="center"><a href="{{url('ceshi3\index')}}">课程列表</a></h3>
<form action="{{url('ceshi3\manage_do')}}" method="post" align="center">
    @csrf
                第一节课: <select name="php" id="">
        <option value="php">php</option>
        <option value="语文">语文</option>
        <option value="数学">数学</option>
        <option value="英语">英语</option>
            </select><br>
                第二节课: <select name="yuwen" id="">
        <option value="php">php</option>
        <option value="语文">语文</option>
        <option value="数学">数学</option>
        <option value="英语">英语</option>
                </select><br>
                第三节课: <select name="shuxue" id="">
        <option value="php">php</option>
        <option value="语文">语文</option>
        <option value="数学">数学</option>
        <option value="英语">英语</option>
                </select><br>
                第四节课: <select name="yingyu" id="">
        <option value="php">php</option>
        <option value="语文">语文</option>
        <option value="数学">数学</option>
        <option value="英语">英语</option>
                </select><br>
    <input type="submit" value="提交">

</form>
</body>
</html>
