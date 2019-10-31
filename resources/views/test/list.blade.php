@extends('layouts.child')

@section('title', '时事新闻展示')

@section('content')
   <table class="table" style="margin: 6%">
       <tr>
           <td>新闻id</td>
           <td>新闻标题</td>
           <td>新闻内容</td>
           <td>新闻时间</td>
           <td>新闻网站</td>
           <td>新闻地址</td>
       </tr>
       <tr>
           <tbody id="list" >

           </tbody>
       </tr>

   </table>
   <script>
       var url = 'http://www.1902.com/api/test/news';
       $.ajax({
           url:url,
           dataType:'json',
           success:function (res) {
               console.log(res);
               $.each(res.result,function (i,v) {
                   var tr = $('<tr></tr>');
                   tr.append('<td>'+i+'</td>');
                   tr.append('<td>'+v.full_title+'</td>');
                   tr.append('<td>'+v.content+'</td>');
                   tr.append('<td>'+v.pdate+'</td>');
                   tr.append('<td>'+v.src+'</td>');
                   tr.append('<td>'+v.url+'</td>');
                   $('#list').append(tr);
               })

           }
       })
   </script>
@endsection
