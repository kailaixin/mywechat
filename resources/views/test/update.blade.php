@extends('layouts.child')

@section('title', '修改页面')

@section('content')

<form class="form-horizontal" align="center" style="margin: 6%" >
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
            <button type="button" class="btn btn-default">修改</button>
        </div>
    </div>
</form>


<script>
    function GetQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }
    var id =GetQueryString("id");//获取传过来的id
    $.ajax({
        url:'http://www.1902.com/api/test/update',
        data:{id:id},
        dataType:'json',
        success:function(req){
            // console.log(req);
            $('[name="stu_name"]').val(req.find.stu_name);
            $('[name="stu_sex"]').val(req.find.stu_sex);
            $('[name="stu_age"]').val(req.find.stu_age);
        }
    });
    $('[type="button"]').click(function () {
        var id =GetQueryString("id");//获取传过来的id
        var stu_name = $('[name="stu_name"]').val();
        var stu_sex = $('input:radio:checked').val();
        var stu_age = $('[name="stu_age"]').val();
        // alert(stu_sex);return;

        $.ajax({
            url:"http://www.1902.com/api/test/update_do",
            data:{id:id,name:stu_name,sex:stu_sex,age:stu_age},
            type:'POST',
            dataType:'json',
            success:function (res) {
                // console.log(res);
                if(res.code==200){
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


