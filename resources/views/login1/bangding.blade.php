<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>微信绑定页面</title>
</head>
<body>
        <h3 align="center">绑定管理员账号</h3>
        <form action="{{url('admin1/bangding_do')}}" align="center" method="post">
            @csrf
            用户名: <input type="text" name="user_name"><br><br>

            密码: <input type="password" name="user_pwd"><br><br>

            <input type="submit" value="绑定管理员账号">
        </form>
</body>
</html>
