@extends('layouts.child')

@section('title', '添加页面')

@section('content')
    <form class="form-horizontal" align="center" style="margin: 6%" enctype="multipart/form-data">
        <div class="form-group" >
            <label for="inputEmail3" class="col-sm-2 control-label">姓名</label>
            <div class="col-sm-10">
                <input type="text" name="stu_name" class="form-control" id="inputPassword3" placeholder="请输入姓名">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">年龄</label>
            <div class="col-sm-10">
                <input type="text" name="stu_age" class="form-control" id="inputPassword3" placeholder="请输入年龄">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">文件上传</label>
            <input type="file" name="stu_image" id="exampleInputFile">
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="radio" name="stu_sex" value="男" checked>男
                        <input type="radio" name="stu_sex" value="女">女
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" class="btn btn-default">添加</button>
            </div>
        </div>
    </form>

    <script>
            var name = '开来鑫';
            var age = '18';
            var mobile = 15500805364;
            var rand = 5588;
            var  url = "http://wym.yingge.fun/api/user/test";
            $.ajax({
                url:url,
                data:{name:name,age:age,mobile:mobile,rand:rand},
                type:'POST',
                contentType:false,
                processData:false,
                dataType:'json',
                success:function (res) {
                    if(res.code==1){
                        alert(res.msg);
                        location.href = 'http://www.1902.com/test/show';
                    }else{
                        alert(res.msg);
                    }
                },
            });
        });

    </script>
@endsection





