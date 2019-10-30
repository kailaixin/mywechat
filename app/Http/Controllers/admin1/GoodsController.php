<?php

namespace App\Http\Controllers\admin1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\category;
use App\model\cate_type;
use App\model\attribute;
use App\model\Goods;
use App\model\goods_attr;
use App\model\item;

class GoodsController extends Controller
{
    //商品属性添加
    public function add()
    {
        $cate_info = category::where(['parent_id'=>0])->get()->toArray();
        $type_info = cate_type::get()->toArray();
//        dump($cate_info);
//        dump($type_info);
        return view('admin1/goods/add',[
                   'cate_info'=>$cate_info,
                    'type_info'=>$type_info,
        ]);
    }
    //商品属性添加处理
    public function add_do(Request $request)
    {
        //商品分类信息
        $category = category::get()->toArray();
//        /接收商品信息
        $post = request()->except('_token');
//        dd($post);
//        判断文件是否存在
        if ($request->hasFile('goods_img')) {
            $goods_img = $request->file('goods_img');
        }
        if(!empty($goods_img)){
            //判断文件是否上传成功
            if ($request->file('goods_img')->isValid()){
                $path = $request->goods_img->store('images');
            }

        $path = "0";

        }

//        dd($path);
        //验证
        $goods_no = MD5(time().rand(0000,9999));
//        dd($goods_no);
//        商品基础信息入库
        $goodsData = Goods::create([
            'goods_name'=>$post['goods_name'],
            'cate_id'=>$post['cate_id'],
            'goods_price'=>$post['goods_price'],
            'goods_img'=>$path,
            'goods_no'=>$goods_no,
            'goods_deta'=>$post['goods_deta'],
        ]);
//        dd($goodsData);save
        //商品属性信息入库
        $goods_id = $goodsData->goods_id;
//        dd($goods_id);
        //商品属性信息入库
        //定义一个空数组
        $array_info=[];
        foreach ($post['goods_attr_value'] as $key=>$value){
//            var_dump($key);die;
            $array_info[] =[
                'goods_id'=>$goods_id,
                'attr_id'=>$post['goods_attr_id'][$key],
                'goods_attr_value'=>$value,
                'goods_attr_price'=>$post['goods_attr_price'][$key],
            ];

        }
        $attrInfo = goods_attr::insert($array_info);
        if($attrInfo){
            return redirect('admin1/goods/item?id='.$goods_id);
        }
//        dump($attrInfo);
    }
    //点击类型 显示属性
    public function change()
    {
        //接收
        $type_id = request()->input('type_id');
//        dd($type_id);
        //查询 接收到类型下的属性
        $data_info =  attribute::where(['type_id'=>$type_id])->get()->toArray();
//        dd($data_info);
        return json_encode(['data_info'=>$data_info,'msg'=>'ok','code'=>'200']);
    }

    //货品添加页面

    public function item(Request $request)
    {
        $id = $request->id;
//       根据goods_id查询出 商品基本信息表
        $goodsDate = Goods::where(['goods_id'=>$id])->first()->toArray();
//       根据goods_id 查询出商品属性关系表(属性值)
        $goodsAttrData = goods_attr::join('attribute','attribute.attr_id','=','goods_attr.attr_id')
                                    ->where(['goods_id'=>$id,'attr_make'=>1])
                                    ->get()
                                    ->toArray();
//        dd($goodsDate);
        $newArray = [];
        foreach ($goodsAttrData as $key => $value){
            $stutas = $value['attr_name'];//定义一个标识 进行属性分组
            $newArray[$stutas][]=$value;
        }
//        echo "<pre>";
//        var_dump($newArray);
       return view('admin1/goods/item',[
           'goodsData'=>$goodsDate,
           'goodsAttrData'=>$goodsAttrData,
           'newArray'=>$newArray,
           'goods_id'=>$id,
       ]);
    }
    //货品添加页面
    public function item_do(Request $request)
    {
        //接收出来的数据
        $postData = $request->except('_token');
        $item_sn = time().rand(0000,9999);
//        dd($item_sn);
        //属性值组合处理数据 求出属性值的数量
        $size =count($postData['goods_attr_list'])  / count($postData['item_number']);
        $goodsAttr = array_chunk($postData['goods_attr_list'],$size);
//        echo "<pre>";
//        var_dump($goodsAttr);die;
//        dump($postData);die;
//        $postData['']
        //验证
        //入库
        $attr_list = [];
        foreach($goodsAttr as $key => $value){
           // dd($goodsAttr);
            item::insert([
                'goods_id'=>$postData['goods_id'],
                'goods_attr_list'=>implode(',',$value),
                'item_sn'=>$item_sn,
                'item_number'=>$postData['item_number'][$key],
            ]);
        }
        return redirect('admin1/goods/show');
    }

    //商品列表展示
    public function show(Request $request)
    {
        //接收搜索信息
        $cate_name = $request->input('cate_name');
        $goods_name = $request->input('goods_name');
        $is_puta = $request->input('is_puta');
        $where = [];
        if(isset($cate_name)){
            $where[]=[
                'cate_name','like',"%$cate_name%",
            ];
        }
        if(isset($goods_name)){
            $where[]=[
                'goods_name','like',"%$goods_name%",
            ];
        }
        if(isset($is_puta)){
            $where[]=[
                'cate_name','like',"%$cate_name%",
            ];
        }
        //查询分类表的所有信息
        $cateInfo = category::get()->toArray();
        //查询商品表和分类表和货品表
        $dataInfo = Goods::leftjoin('category','goods.cate_id','=','category.cate_id')
//                          ->leftjoin('item','goods.goods_id','=','item.goods_id')
                          ->where($where)
                          ->select('goods.*','category.cate_name')
                          ->paginate(3);

//        dd($dataInfo);
        return view('admin1/goods/show',[
            'dataInfo'=>$dataInfo,
            'cateInfo'=>$cateInfo,
        ]);
    }
}
