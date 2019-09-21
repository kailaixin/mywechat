<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>微信登录</title>
</head>
<body>
<form action="{{url('ceshi1/wechat_login_do')}}" method="post" align="center">
    @csrf
    第三方登录: <input type="submit" value="微信登录">
</form>
</body>
</html>
