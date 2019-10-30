@extends('layouts.child')

@section('title', '商品分类')



@section('content')
    <form style="margin:6%" method="post" action="{{url('admin1/cate/add_do')}}">
        @csrf
        <div class="form-group" >
            <label for="exampleInputEmail1">分类名称</label>
            <input type="text" name="cate_name" class="form-control" id="exampleInputEmail1" placeholder="请输入分类名称">
        </div>
        <div class="form-group">
            <select name="parent_id" class="form-control">
                <option value="0" selected>上级分类</option>
               @foreach($cate_info as $v)
                <option value="{{$v['cate_id']}}">{{$v['cate_name']}}</option>
               @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">确定</button>
    </form>
@endsection
