<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>课程管理修改页面</title>
</head>
<body>
<h3 align="center"><a href="{{url('ceshi3\index')}}">课程列表</a></h3>
<form action="{{url('ceshi3\update_do')}}" method="post" align="center">
    <input type="hidden" name="ke_id" value="{{$data->ke_id}}">
    @csrf
    第一节课: <select name="php" id="">
        <option value="php"  @if($data->php =='php') selected @endif>php</option>
        <option value="语文"  @if($data->php =='语文') selected @endif>语文</option>
        <option value="数学" @if($data->php =='数学') selected @endif >数学</option>
        <option value="英语" @if($data->php =='英语') selected @endif>英语</option>
    </select><br>
    第二节课: <select name="yuwen" id="">
        <option value="php"  @if($data->yuwen =='php') selected @endif>php</option>
        <option value="语文"  @if($data->yuwen =='语文') selected @endif>语文</option>
        <option value="数学" @if($data->yuwen =='数学') selected @endif >数学</option>
        <option value="英语" @if($data->yuwen =='英语') selected @endif>英语</option>
    </select><br>
    第三节课: <select name="shuxue" id="">
        <option value="php"  @if($data->shuxue =='php') selected @endif>php</option>
        <option value="语文"  @if($data->shuxue =='语文') selected @endif>语文</option>
        <option value="数学" @if($data->shuxue =='数学') selected @endif >数学</option>
        <option value="英语" @if($data->shuxue =='英语') selected @endif>英语</option>
    </select><br>
    第四节课: <select name="yingyu" id="">
        <option value="php"  @if($data->yingyu =='php') selected @endif>php</option>
        <option value="语文"  @if($data->yingyu =='语文') selected @endif>语文</option>
        <option value="数学" @if($data->yingyu =='数学') selected @endif >数学</option>
        <option value="英语" @if($data->yingyu =='英语') selected @endif>英语</option>
    </select><br>
    <input type="submit" value="修改">

</form>
</body>
</html>

