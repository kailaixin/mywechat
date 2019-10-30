@extends('layouts.child')

@section('title', '商品分类展示')


@section('content')
   <table  class="table table-striped" style="margin:6%">
       <tr>
           <th>编号</th>
           <th>分类名称</th>
           <th>是否显示</th>
           <th>是否排序</th>
           <th>操作</th>
       </tr>
       @foreach($cate_info as $v)
       <tr>

           <td>{{$v['cate_id']}}</td>
           <td>{{str_repeat('--',$v['level']).$v['cate_name']}}</td>
           <td>{{$v['is_show']}}</td>
           <td>{{$v['is_sort']}}</td>
           <td><a href="" class="btn btn-success">编辑</a>|<a href="" class="btn btn-danger">删除</a></td>
       </tr>
       @endforeach
   </table>
@endsection

