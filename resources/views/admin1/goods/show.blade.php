@extends('layouts.child')

@section('title', '商品列表展示页')

@section('content')
    <form class="form-inline" action="{{'show'}}" method="get" style="margin: 6%">
        <div class="form-group">
            <select id="disabledSelect" name="cate_name" class="form-control">
                <option>所有分类</option>
                @foreach($cateInfo as $v)
                    <option value="{{$v['cate_name']}}">{{$v['cate_name']}}</option>
                @endforeach

            </select>
        </div>
        <div class="form-group">
            <select id="disabledSelect" name="is_puta" class="form-control">
                <option>是否上架</option>
                <option value="1">上架</option>
                <option value="0">未上架</option>
            </select>
            <label for="disabledTextInput">关键字</label>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="goods_name" id="exampleInputPassword1" placeholder="请输入搜索的关键字">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
<table class='table table-bordered'>
    <tr>
        <th>id标号</th>
        <th>商品名称</th>
        <th>分类名称</th>
        <th>商品价格</th>
        <th>是否上架</th>
        <th>操作</th>
    </tr>
    @foreach($dataInfo as $key => $value)
    <tr>

            <td>{{$value->goods_id}}</td>
            <td>{{$value->goods_name}}</td>
            <td>{{$value->cate_name}}</td>
            <td>{{$value->goods_price}}</td>
            <td>@if($value->is_puta == 1) 上架 @else 未上架 @endif</td>
            <td><a href="">编辑</a> | <a href="">删除</a></td>
    </tr>
    @endforeach
    <tr align="center">
        <td colspan="6">
            {{ $dataInfo->links() }}
        </td>
    </tr>
</table>

@endsection
