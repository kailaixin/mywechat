@extends('layouts.child')

@section('title', '添加属性')



@section('content')
    <h3 align="center"><a href="{{url('admin1/attr/show')}}">点击展示属性列表</a></h3>
    <form style="margin:6%" method="post" action="{{url('admin1/attr/add_do')}}">
        @csrf
        <div class="form-group" >
            <label for="exampleInputEmail1">属性名称</label>
            <input type="text" name="attr_name" class="form-control" id="exampleInputEmail1" placeholder="请输入属性名称">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">所属商品属性</label>
            <select name="type_id" class="form-control">
                @foreach($cate_type as $v)
                <option value="{{$v['type_id']}}">{{$v['type_name']}}</option>
                    @endforeach
            </select>
        </div>
        <label for="exampleInputEmail1">属性是否可选</label>
        <div class="radio">
            <label>
                <input type="radio" name="attr_make" id="optionsRadios1" value="1" checked>可选
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="attr_make" id="optionsRadios2" value="2">不可选
            </label>
        </div>

        <button type="submit" class="btn btn-success">确定</button>
    </form>
@endsection
