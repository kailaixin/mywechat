@extends('layouts.child')

@section('title', '注册页面')

@section('content')
    <form style="margin: 6%" action="{{url('ceshi4/reg_do')}}" method="post">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">用户名</label>
            <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="请输入要注册的用户名">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">用户密码</label>
            <input type="password" name="userpwd" class="form-control" id="exampleInputPassword1" placeholder="请输入注册密码">
        </div>

        <input type="submit" class="btn btn-default" value="注册">
    </form>

@endsection

