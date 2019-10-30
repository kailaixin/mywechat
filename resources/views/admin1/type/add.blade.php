@extends('layouts.child')

@section('title', '添加商品类型')

@section('content')
    <h3 align="center"><a href="{{url('admin1/type/show')}}">点击展示类型列表</a></h3>
    <form style="margin:6%" method="post" action="{{url('admin1/type/add_do')}}">
        @csrf
        <div class="form-group" >
            <label for="exampleInputEmail1">类型名称</label>
            <input type="text" name="type_name" class="form-control" id="exampleInputEmail1" placeholder="请输入类型名称">
        </div>

        <button type="submit" class="btn btn-success">确定</button>
    </form>
@endsection
