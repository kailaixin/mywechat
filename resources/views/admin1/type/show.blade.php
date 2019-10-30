@extends('layouts.child')

@section('title', '商品类型展示')


@section('content')
            <h3 align="center"><a href="{{url('admin1/type/add')}}">点击添加商品类型</a></h3>

   <table  class="table table-striped" style="margin:6%">
       <tr>
           <th>编号</th>
           <th>类型名称</th>
           <th>操作</th>
       </tr>
       @foreach($type_info as $v)
       <tr>
           <td>{{$v['type_id']}}</td>
           <td>{{$v['type_name']}}</td>
           <td><a href="" class="btn btn-success">编辑</a><a href="" class="btn btn-danger">删除</a></td>

       </tr>
       @endforeach

   </table>

@endsection

