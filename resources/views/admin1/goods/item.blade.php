@extends('layouts.child')

@section('title', '货品添加页')

@section('content')
    <h3>货品添加</h3>
    <form action="{{url('admin1/goods/item_do')}}" method="post">
        @csrf
        <input type="hidden" name="goods_id" value="{{$goods_id}}">
    <table width="100%" id="table_list" class='table table-bordered'>
        <tbody>
        <tr>
            <th colspan="20" scope="col">商品名称:{{$goodsData['goods_name']}} &nbsp;&nbsp;&nbsp;&nbsp;货号：ECS000075</th>
        </tr>

        <tr>
            <!-- start for specifications -->
            @foreach($newArray as $key => $value)
            <td scope="col"><div align="center"><strong>{{$key}}</strong></div></td>
            @endforeach
            <!-- end for specifications -->
            <td class="label_2">库存</td>
            <td class="label_2">&nbsp;</td>
        </tr>

        <tr id="attr_row">
            <!-- start for specifications_value -->
            @foreach($newArray as $key => $value)
            <td align="center" style="background-color: rgb(255, 255, 255);">
                <select name="goods_attr_list[]">
                    <option value="" selected="">请选择...</option>
                    @foreach($value as $k=> $v)
                    <option value="{{$v['goods_attr_id']}}">{{$v['goods_attr_value']}}</option>
                    @endforeach
                </select>
            </td>
            @endforeach

            <!-- end for specifications_value -->
            <td class="label_2" style="background-color: rgb(255, 255, 255);"><input type="text" name="item_number[]" value="1" size="10"></td>
            <td style="background-color: rgb(255, 255, 255);"><input type="button" class="butt" value="+" ></td>
        </tr>

        <tr>
            <td align="center" colspan="5" style="background-color: rgb(255, 255, 255);">
                <input type="submit" class="button" value=" 保存 ">
            </td>
        </tr>
        </tbody>
    </table>
    </form>
    <script>
        //
        $(document).on('click','.butt',function () {
            // alert(1231);
            var val = $(this).val();
            if(val == '+'){
                $(this).val('-');
                var clone_tr = $(this).parent().parent().clone();//克隆一个tr 带-号的
                $(this).val('+');//再赋予本身为+
                $(this).parent().parent().after(clone_tr);
            }else{
                $(this).parent().parent().remove(clone_tr);
            }

          //追加clone_tr

        });
    </script>
@endsection
