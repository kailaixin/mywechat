@extends('layouts.child')

@section('title', '合同')


@section('content')
    <form action="" style="margin:6%">
        <table border="1" align="center" width="900" height="200" >
            <tr>
                <td colspan="2" align="center">合同</td>
                <td></td>
            </tr>
            <tr>
               <td>客户名称:</td>
                <td><input type="text" name="client"></td>
            </tr>
            <tr>
                <td>成交情况</td>
                <td>
                    <span>成交金额:</span>
                    <span><input type="text"></span>: <span style="color:red">万元</span>&nbsp;&nbsp;&nbsp;
                    <span>开始时间:</span>
                    <span><input type="date" name="time"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>结束时间:</span>
                    <span><input type="date"></span><br><br><br>
                    <span>服务项目:</span>
                    <span>新增服务项目</span><br><br>
                    <span>类型:</span>
                    <span>
                        <select name="" id="">
                            <option value="">广告</option>
                        </select>
                    </span>&nbsp; &nbsp;
                    <span>内容:</span>
                    <span><textarea name="" id="" cols="0" rows="0"></textarea></span> &nbsp;&nbsp;
                    <span>金额:</span>
                    <span><input type="text"></span><span style="color:darkred">万元</span>&nbsp;&nbsp;
                    <span><a href="">删除</a></span><br><br>
                    <span>类型:</span>
                    <span>
                        <select name="" id="">
                            <option value="">网页制作</option>
                        </select>
                    </span>&nbsp; &nbsp;
                    <span>内容:</span>
                    <span><textarea name="" id="" cols="0" rows="0">精美网站制作一套</textarea></span> &nbsp;&nbsp;
                    <span>金额:</span>
                    <span><input type="text"></span><span style="color:darkred">万元</span>&nbsp;&nbsp;
                    <span><a href="">删除</a></span>

                </td>
            </tr>

        </table>


    </form>
@endsection
