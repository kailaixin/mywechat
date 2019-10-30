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
    $('[type="button"]').click(function () {

        var stu_name = $('[name="stu_name"]').val();
        var stu_sex = $('input:radio:checked').val();
        var stu_age = $('[name="stu_age"]').val();

        //实例化一个表单对象
        var file_data = new FormData();
        //接收文件的信息
        var file = $('[name="stu_image"]')[0].files[0];
        // console.log(file);return;
        //将获取到的文件信息 加入到实例化的表单对象中
        file_data.append('stu_image',file);//在from表单里添加字段
        file_data.append('name',stu_name);//在from表单里添加字段
        file_data.append('sex',stu_sex);//在from表单里添加字段
        file_data.append('age',stu_age);//在from表单里添加字段
        // console.log (file_data);
        var  url = "http://www.1902.com/api/test/add";
        $.ajax({
            url:url,
            data:file_data,
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





