<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户标签群发消息</title>
</head>
<body>
        <h1 align="center"><a href="{{url('ceshi1/tag_list')}}">群发消息</a></h1><br>
<form action="{{url('ceshi1/tag_word_do')}}" method="post" align="center">
    @csrf
    <input type="hidden" name="tagid" value="{{$tag_id}}">
    <table border="1" align="center">
        <tr>
            <td>群发消息</td>
            <td><textarea name="word" id="" cols="30" rows="10"></textarea> </td>
        </tr>
        <br>
        <tr>
            <td><input type="submit" value="群发"></td>
        </tr>

    </table>
</form>
</body>
</html>
