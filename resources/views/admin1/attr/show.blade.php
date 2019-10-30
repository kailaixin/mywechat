@extends('layouts.child')

@section('title', '商品属性展示')


@section('content')
            <h3 align="center"><a href="{{url('admin1/attr/add')}}">点击添加属性</a></h3>
            <form class="form-inline" align="center" action="{{url('admin1/attr/show')}}" >
                <div class="form-group">
                    <label for="exampleInputName2"></label>
                    <input type="text" name="attr_name" class="form-control" id="exampleInputName2" placeholder="请输入搜索信息">
                </div>
                <button type="submit" class="btn btn-default">搜索</button>
            </form>
   <table  class="table table-striped" style="margin:6%">
       <tr>
           <th><input type="checkbox" id="dele">编号</th>
           <th>属性名称</th>
           <th>商品类型</th>
           <th>属性划分</th>
           <th>操作</th>
       </tr>
       @foreach($attribute as $v)
       <tr>

           <td><input type="checkbox" name="checkboxes[]" id="del" value="{{$v->attr_id}}">{{$v->attr_id}}</td>
           <td>{{$v->attr_name}}</td>
           <td>{{$v->type_name}}</td>
           <td>@if($v->attr_make==1) 可选属性 @else 不可选属性 @endif</td>
           <td><a href="" class="btn btn-success">属性列表</a><a href="" class="btn btn-danger">删除</a></td>

       </tr>
       @endforeach
       <tr>
           <td>
               <input class="btn btn-danger" type="button" value="批删">
           </td>
       </tr>

   </table>
            <center> {{$attribute->links()}}</center>
   <script>
       $(function(){
        $(document).on('click','[type="button"]',function(){
            var _this = $(this);
            var obj = $('[name="checkboxes[]"]:checked');//获取 默认选中的选项
            var del = new Array();//新定义一个数组 存储获取的id
            // console.log(obj);
            //循环数组 放入新定义的数组内
            $.each(obj,function (i,v) {
                 var id = $(this).val();//循环一次获取一次
                // console.log(id);
                del.push(id);
            });
            $.ajax({
                url:"{{url('admin1/attr/delete')}}",
                data:{del:del},
                dataType:'json',
                success:function (res) {
                    if(res.code == 200){
                        alert(res.msg);
                       history.go(0);
                    }else{
                        alert(res.msg);
                    }
                }
            });

        });
       });

   </script>
@endsection

