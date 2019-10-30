@extends('layouts.child')

@section('title', '商品展示')



@section('content')
    <table class="table table-striped" style="margin: 6%">
        <tr>
            <th>商品id</th>
            <th>商品名称</th>
            <th>商品价格</th>
            <th>商品图片</th>
            <th>操作</th>
        </tr>
      <tr>
          <tbody id="list" >

          </tbody>
      </tr>
        <tr>
            <td colspan="6" align="center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">


                    </ul>
                </nav>
            </td>

        </tr>
    </table>
    <script src="/js/jquery.js"> </script>
    <script>
        //分页
        $(document).on('click','.pagination a',function(){
            // var = name = $('[]').val();
            // var = name = $('[]').val();
            // var = name = $('[]').val();
            var page = $(this).text();
            // alert(page);
            var url1 = 'http://www.1902.com/api/test1/show';
            $.ajax({
               url:url1,
               data:{page:page},
               dataType: 'json',
                success:function(res){
                   // alert(res);
                    page_fa(res);
                }

            });
        });

        //展示页面
        $(function(){

            var url1 = 'http://www.1902.com/api/test1/show';
            $.ajax({
                url:url1,
                dataType:'json',
                success:function(res){
                 page_fa(res);
                },

            });
        });

function page_fa(res){
    $('#list').empty();
    $.each(res.data.data,function(k,v) {
        var tr= $('<tr></tr>');//创建一个tr
        tr.append('<td>'+v.goods_id+'</td>');
        tr.append('<td>'+v.goods_name+'</td>');
        tr.append('<td>'+v.goods_price+'</td>');
        tr.append('<td><img src="http://www.1902.com/tupian/'+v.goods_img+'" height="52" alt=""></td>');
        tr.append('<td><a class="btn btn-success" href="">编辑</a>|<a href="" class="btn btn-danger">删除</a></td>')
        $('#list').append(tr);
    });
    //找到最大的页码
    var max_page =res.data.last_page;
    $('.pagination').empty();
    for (var i =1;i <=max_page;i++){
        var li = "<li><a href='#'>"+i+"</a></li>";
        $(".pagination").append(li);
    }
}
    </script>
@endsection

