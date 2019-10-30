<?php

namespace App\Http\Controllers\admin1;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\cate_type;
use App\model\attribute;

class AttrController extends Controller
{
    /*
     * 属性的添加
     * */
    public function add()
    {
        $cate_type = cate_type::get()->toArray();
//        dd($cate_type);
        return view('admin1/attr/add',['cate_type'=>$cate_type]);
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
        $res = attribute::insert($post);
        if($res){
            return redirect('admin1/attr/show');
        }else{
            return redirect('admin1/attr/add');
        }
    }

    /*
     * 属性添加的展示
     * */
    public function show()
    {
        $attr_name = request()->input('attr_name');
//        dump($attr_name);
        $where=[];
        if(isset($attr_name)){
            $where[]=[
              'attr_name','like',"%$attr_name%",
            ];
        }
        $type_info = cate_type::get();
        $attribute = attribute::join('cate_type','attribute.type_id','=','cate_type.type_id')
            ->select('attribute.*','cate_type.type_name')
            ->where($where)
            ->paginate(5);
//        dd($attribute);
        return view('admin1/attr/show',['attribute'=>$attribute,'attr_name'=>$attr_name,'type_info'=>$type_info]);
    }
    //批量删除
    public function delete()
    {
        //接收
        $del_id = request()->del;
        dd($del_id);
//        删除
        $res = attribute::destroy($del_id);
//        dd($res);
        if($res){
            return json_encode(['msg'=>'删除成功','code'=>200]);
        }else{
            return json_encode(['msg'=>'删除失败','code'=>201]);
        }
    }
}
