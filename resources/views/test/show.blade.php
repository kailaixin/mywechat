<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="/js/jquery.js"></script>
    <title>展示页面</title>
</head>
<body>
<center>
<form action="" style="margin:6%">
    <div class="form-group">
        <label for="exampleInputEmail1">姓名</label>
        <input type="email" name="stu_name" class="form-control" id="exampleInputEmail1" placeholder="请输入要搜索的姓名">
    </div>
    <div class="checkbox">
        <label>
            <input type="radio" name="stu_sex" value="男"> 男
            <input type="radio" name="stu_sex" value="女"> 女
        </label>
    </div>

    <input type="button" id="sub" class="btn btn-primary" value="搜索">

        <table border="1" style="margin:2%" class="table table-striped table-hover">
            <tr>
                <th>id</th>
                <th>姓名</th>
                <th>头像</th>
                <th>性别</th>
                <th>年龄</th>
                <th>操作</th>
            </tr>

            <tr>
                <tbody id="list" >

                </tbody>
            </tr>

            <tr>
                <td colspan="6" align="center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">

                        </ul>
                    </nav>
                </td>

            </tr>

        </table>

</form>
</center>
</body>

</html>
<script>
    //分页
    $(document).on('click',".pagination a",function(){
       // 获取页码
//获取搜索值
        var name = $('[name="stu_name"]') .val();
        var sex = $("input[type='radio']:checked").val();
        var page = $(this).text();
        // alert(page);
        var url = 'http://www.1902.com/api/test/show';
        $.ajax({
            url:url,
            data:{page:page,name:name,sex:sex},
            type:'get',
            dataType:'json',
            success:function(res){
                 pageData(res);
            },

        });
    });
    //搜索
    $('#sub').click(function(){
        // alert(123);
        //获取搜索值
        var name = $('[name="stu_name"]') .val();
        var sex = $("input[type='radio']:checked").val();
        // alert(name);
        // alert(sex);
        var url = 'http://www.1902.com/api/test/show';
        $.ajax({
            url:url,
            data:{name:name,sex:sex},
            dataType:'json',
            success:function(res){
                pageData(res);
            }
        });
    });

    //查询展示
    var urll="http://www.1902.com/api/test/show";
    $.ajax({
       url:urll,
        dataType:'json',
        success:function(res){
           // console.log(res);
         pageData(res);
        }
    });
    //封装分页方法   分页
    function pageData(res){
        $('#list').empty();
        $.each(res.data.data,function(k,v) {
            // console.log(v);
            var tr = $("<tr></tr>");
            tr.append('<td>'+v.stu_id+'</td>');
            tr.append('<td>'+v.stu_name+'</td>');
            tr.append('<td><img src="\\'+v.stu_image+'" width="80" height="52" alt=""></td>');
            tr.append('<td>'+v.stu_sex+'</td>');
            tr.append('<td>'+v.stu_age+'</td>');
            tr.append('<td><a class="upd btn btn-success" href="javascript:;"  id="'+v.stu_id+'">修改</a>||<a class="delete btn btn-danger"  id="'+v.stu_id+'" href="javascript:;">删除</a></td>');
            $('#list').append(tr);
        });
        //页面分页页码渲染
        //找到最大页码
        var max_page =res.data.last_page;
        $('.pagination').empty();
        for (var i =1;i <=max_page;i++){
            var li = "<li><a href='#'>"+i+"</a></li>";
            $(".pagination").append(li);
        }

    }
    //把id值传到修改接口
    //修改功能
    $(document).on('click',".upd",function(){
          // alert(123);return;
        var id=$(this).attr('id');
        // alert(id);
        window.location.href='http://www.1902.com/test/update?id='+id;
    });
    // //删除功能
    $(document).on('click',".delete",function(){
        // alert(123);return;
        var id=$(this).attr('id');
        // alert(id);return;
        $.ajax({
            url:'http://www.1902.com/api/test/delete?id='+id,
            data:{id:id},
            dataType:'json',
            success:function(res){
              // console.log(res);
                if(res.code==200){
                    alert(res.msg);
                }
            },
        });
    });
</script>
