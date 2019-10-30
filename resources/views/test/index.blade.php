@extends('layouts.child')

@section('title', '数组数据处理测试')



@section('content')
    <table class="table table-striped" style="margin: 6%">
        <tr>
            <td>班级id</td>
            <td>班级名称</td>
            <td>班级学生总人数</td>
        </tr>
        @foreach($classInfo as $key=>$value )
        <tr>
            <td>{{$value['class_id']}}</td>
            <td>{{$value['class_name']}}</td>
            <td>{{$value['count']}}</td>
        </tr>
            @endforeach
    </table>

@endsection

