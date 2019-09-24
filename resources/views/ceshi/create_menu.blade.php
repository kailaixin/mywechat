<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加菜单列表</title>
</head>
<body>
        <h1 align="center"><a href="">菜单列表</a></h1>
        <form action="{{url('ceshi2\create_menu_do')}}" method="post" align="center">
            @csrf
            一级菜单: <input type="text" name="name1"><br>
            二级菜单: <input type="text" name="name2"><br>
            菜单类型click或view: <select name="type" id="">
                <option value="click"> click </option>
                <option value="view"> view </option>
            </select><br>
            事件值: <input type="text" name="event_value"><br>
            <input type="submit" value="提交">

        </form>
</body>
</html>
