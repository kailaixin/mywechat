<?php

namespace App\Http\Controllers\admin1;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\cate_type;
use App\model\attribute;

class TypeController extends Controller
{
    /*
     * 属性的添加
     * */
    public function add()
    {
        return view('admin1/type/add');
    }

    /*
     * 属性添加的处理
     * */
    public function add_do(Request $request)
    {
        //接收
        $post = $request->except('_token');
//        dd($post);
        //验证
        //入库
        $res = cate_type::insert($post);
        if($res){
            return redirect('admin1/type/show');
        }else{
            return redirect('admin1/type/add');
        }
    }

    /*
     * 属性添加的展示
     * */
    public function show()
    {
        $type_info = cate_type::get()->toArray();
//        dd($type_info);
        return view('admin1/type/show',['type_info'=>$type_info]);
    }
}
