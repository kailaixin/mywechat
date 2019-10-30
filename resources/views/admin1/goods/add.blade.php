@extends('layouts.child')

@section('title', '商品添加页')

@section('content')
    <h3>商品添加</h3>
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="javascript:;" name='basic'>基本信息</a></li>
        <li role="presentation" ><a href="javascript:;" name='attr'>商品属性</a></li>
        <li role="presentation" ><a href="javascript:;" name='detail'>商品详情</a></li>
    </ul>
    <br>
    <form action="{{url('admin1/goods/add_do')}}" method="post" enctype="multipart/form-data" id='form'>
        @csrf
        <div class='div_basic div_form'>
            <div class="form-group">
                <label for="exampleInputEmail1">商品名称</label>
                <input type="text" class="form-control" name='goods_name'>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">商品分类</label>
                <select class="form-control" name='cate_id'>
                    <option value='0'>请选择</option>
                    @foreach($cate_info as $v)
                        <option value='{{$v['cate_id']}}'>{{$v['cate_name']}}</option>
                        @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">商品货号</label>
                <input type="text" class="form-control" name='goods_no'>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">商品价钱</label>
                <input type="text" class="form-control" name='goods_price'>
            </div>

            <div class="form-group">
                <label for="exampleInputFile">商品图片</label>
                <input type="file" name='goods_img'>
            </div>
        </div>
        <div class='div_detail div_form' style='display:none'>
            <div class="form-group">
                <label for="exampleInputFile">商品详情</label>
                <textarea class="form-control" name="goods_deta" rows="3"></textarea>
            </div>
        </div>
        <div class='div_attr div_form' style='display:none'>
            <div class="form-group">
                <label for="exampleInputEmail1">商品类型</label>
                <select class="form-control" name='type_id' >
                    <option>请选择</option>
                    @foreach($type_info as $v)
                        <option value='{{$v['type_id']}}'>{{$v['type_name']}}</option>
                    @endforeach
                </select>
            </div>
            <br>

            <table width="100%" id="attrTable" class='table table-bordered'>

            </table>
        </div>

        <button type="submit" class="btn btn-default" id='btn'>添加</button>
    </form>

    <script type="text/javascript">
        //标签页 页面渲染
        $(".nav-tabs a").on("click",function(){
            $(this).parent().siblings('li').removeClass('active');
            $(this).parent().addClass('active');
            var name = $(this).attr('name');  // attr basic
            $(".div_form").hide();
            $(".div_"+name).show();  // $(".div_"+name)
        });
        //点击商品类型 获取其商品属性
        $('[name="type_id"]').on('change',function () {
             var  type_id = $(this).val();
             // alert(type_id);

            $.ajax({
                url:"{{url('admin1/goods/change')}}",
                data:{type_id:type_id},
                dataType:'json',
                type:'post',
                success: function(res){
                    // console.log(res);
                    $('#attrTable').empty();
                    $.each(res.data_info,function (i,v) {
                        //判断是可选或不可选
                        if(v.attr_make == 1){//为可选
                            var tr = '<tr>\n' +
                                '                    <td><a href="javascript:;" class="addRow">[+]</a>'+v.attr_name+'</td>\n' +
                                '                    <td>\n' +
                                '                        <input type="hidden" name="goods_attr_id[]" value="'+v.attr_id+'">\n' +
                                '                        <input type="text" name="goods_attr_value[]" value="" size="20">\n' +
                                '                        属性价格 <input type="text" name="goods_attr_price[]" value="" size="5" maxlength="10">\n' +
                                '                    </td>\n' +
                                '                </tr>';
                            $('#attrTable').append(tr);

                        }else{
                            //构建一个tr
                            var tr = ' <tr>\n' +
                                '                    <td>'+v.attr_name+'</td>\n' +
                                '                    <td>\n' +
                                '                        <input type="hidden" name="goods_attr_id[]" value="'+v.attr_id+'">\n' +
                                '                        <input type="text"  name="goods_attr_value[]"value="" size="20">\n' +
                                '                        <input type="hidden" name="goods_attr_price[]" value="0">\n' +
                                '                    </td>\n' +
                                '                </tr>';
                            // console.log(tr);
                            $('#attrTable').append(tr);
                        }

                    });
                },
            });
        });
        //+ -号 效果 操作
        $(document).on('click','.addRow',function () {
           // alert(1231);
            var val = $(this).html();
            // alert(val);
            if(val == '[+]'){//如果是[+]号就克隆
                $(this).html('[-]');//复制克隆需要-号
                var tr_clone = $(this).parent().parent().clone();
                $(this).html('[+]');
                $(this).parent().parent().after(tr_clone);
            }else{//否则就移除
                $(this).parent().parent().remove(tr_clone);
            }
        });
    </script>
@endsection
