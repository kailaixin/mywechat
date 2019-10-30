@extends('layouts.child')

@section('title', '商品添加')


@section('content')
    <form style="margin:6%">
        <div class="form-group">
            <label for="exampleInputEmail1">商品名称</label>
            <input type="input" name="goods_name" class="form-control" id="exampleInputEmail1" placeholder="请输入商品名称">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">商品价钱</label>
            <input type="input" name="goods_price" class="form-control" id="exampleInputPassword1" placeholder="请输入商品价钱">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">商品图片</label>
            <input type="file" name="goods_img" id="exampleInputFile">
        </div>

        <input type="button" id="btu" class="btn btn-success" value="商品添加">
    </form>
    <script src="/js/jquery.js"></script>
    <script>
        $(function(){//页面加载事件
            $('#btu').click(function(){
                event.preventDefault();
                var fd = new FormData();//建立空表单
                var name = $('[name="goods_name"]').val();
                var price = $('[name="goods_price"]').val();
                var img = $('[name="goods_img"]')[0].files[0];
                // console.log(img);
                fd.append('goods_img',img);//往form表单里添加字段
                fd.append('name',name);//往form表单里添加字段
                fd.append('price',price);//往form表单里添加字段
                var url = 'http://www.1902.com/api/test1/add';
                $.ajax({
                    url:url,
                    data:fd,
                    dataType:'json',
                    type:'POST',
                    contentType:false,
                    processData:false,
                    success:function(res){
                        // alert(res);
                        if(res.code==200){
                            alert(res.msg);
                           window.location.href='http://www.1902.com/test1/show';
                        }
                    },
                });
            });
        });
    </script>

@endsection
