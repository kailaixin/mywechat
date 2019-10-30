@extends('layouts.child')

@section('title', '查询时事新闻')

@section('content')
    <!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"><link rel="icon" href="https://jscdn.com.cn/highcharts/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<form class="form-inline" align="center" style="margin: 6%">
    <div class="form-group">
        <label for="exampleInputName2"></label>
        <input type="text" class="form-control" name="name" id="exampleInputName2" placeholder="请输入要查询的新闻">
    </div>

    <button type="submit" class="btn btn-success">查询</button>

</form>
<script>
    $('.btn-success').on('click',function () {
        // alert(123);
        var name = $('[name="name"]').val();
        var url = 'http://www.1902.com/api/test1/test';
        // alert(name);return;
        $.ajax({
            url:url,
            data:{name:name},
            dataType:'json',
            type:'post',
            success:function(res){
                console.log(res);

            },

        });
        return false;
    })
</script>
@endsection
