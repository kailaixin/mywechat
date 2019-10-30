<?php

namespace App\Http\Controllers\admin1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\category;
use App\Tools\Tools;
use Validator;

class CateController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /*
     * 分类添加
     * */
    public function add()
    {
        $cate_info = category::get()->toArray();
//        dd($cate_info);
        $cate_info = $this->tools->getTree($cate_info);
//        dd($cate_info);
        return view('admin1/cate/add',["cate_info"=>$cate_info]);
    }
    /*
     * 添加分类处理
     * */
    public function add_do(Request $request)
    {
        //接收值
        $post = $request->except('_token');
//        dd($post);
        //验证
        $validator=Validator::make($post, [
            'cate_name' => 'required|unique:category',

        ],[
            'cate_name.required'=>'分类名称不能为空',
            'cate_name.unique'=>'分类名称已存在',

        ]);
        if ($validator->fails()) {
            return redirect('admin1/cate/add')
                ->withErrors($validator)
                ->withInput();
        }
        //入库
        $res = category::insert($post);
        if($res){
            return redirect('admin1/cate/show');
        }else{
            return redirect('admin1/cate/add');
        }
    }

    /*
     * 分类展示
     * */
    public function show()
    {
        $cate_info = category::get()->toArray();
        $cate_info = $this->tools->getTree($cate_info);
//        dd($cate_info);
        return view('admin1/cate/show',['cate_info'=>$cate_info]);
    }
}
