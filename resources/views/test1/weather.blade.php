@extends('layouts.child')

@section('title', '查询天气')

@section('content')
    <!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"><link rel="icon" href="https://jscdn.com.cn/highcharts/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* css 代码  */
    </style>
    <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
    <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
</head>
<body>

<form class="form-inline" align="center" style="margin: 6%">
    <div class="form-group">
        <label for="exampleInputName2"></label>
        <input type="text" class="form-control" name="city" id="exampleInputName2" placeholder="请输入要查询的城市">
    </div>

    <button type="submit" class="btn btn-success">查询</button>

</form>
<!-- highcharts 天气图标容器 -->
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    //进入当前页 默认展示北京气温
    var url = 'http://www.1902.com/api/test1/weather';
    $.ajax({
        url:url,
        data:{city:"北京"},
        type:'post',
        dataType: 'json',
        success:function(res){
        weather(res.result);
        //     console.log(res);
        }
    });
    $(function(){
        $('[type="submit"]').click(function(){
            event.preventDefault();
            var city = $('[name="city"]').val();
            if(city==''){
                alert('请填写查询的城市');
                return;
            }
            //正则效验 只能是汉字和拼音
            var reg = /^[a-zA-Z]+$|^[\u4e00-\u9fa5]+$/;
            var res = reg.test(city);
            if(!res){
                alert('城市名字只能是拼音和汉字');
                return;
            }
            var url = 'http://www.1902.com/api/test1/weather';

            $.ajax({
                url:url,
                data:{city:city},
                type:'POST',
                dataType:'json',
                success:function(res){
                    // console.log(res);
                    weather(res.result);
                }
            });
        });
    });
    //js代码
    function weather(weatherData)
    {
        console.log(weatherData);
        var categories = [];//x轴日期;
        var data = [];//x轴日期对应的最高最低气温;
        $.each(weatherData,function(i,v){
            categories.push(v.week);
            var arr = [parseInt(v.temp_low),parseInt(v.temp_high)];//将最高和最低气温存放到一个容器里进行循环
            data.push(arr);//将最高,低气温放到定义的变量里
        })

        var chart = Highcharts.chart('container', {
            chart: {
                type: 'columnrange', // columnrange 依赖 highcharts-more.js
                inverted: true
            },
            title: {
                text: '一周天气气温变化'
            },
            subtitle: {
                text: weatherData[0]['citynm']
            },
            xAxis: {
                categories:categories
            },
            yAxis: {
                title: {
                    text: '温度 ( °C )'
                }
            },
            tooltip: {
                valueSuffix: '°C'
            },
            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '°C';
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '温度',
                data: data
            }]
        });
    }
</script>
</body>
</html>



@endsection
