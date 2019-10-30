<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\model\category;
use App\model\cate_type;
use App\model\attribute;
use App\model\Goods;
use App\model\goods_attr;
use App\model\item;
use App\model\api_user;
use App\model\api_cart;


class goodscontroller extends Controller
{
    //ipa
    public function news ()
    {
        //查询商品
        $goodsData = Goods::orderBY('goods_id','desc')->limit(4)->get()->toArray();
        foreach ($goodsData as $key => $value){
            $path_info = 'http://www.1902.com/tupian/';
            $goodsData[$key]['goods_img'] = $path_info.$value['goods_img'];
        }
//        dump($goodsData);
        $goodsData = json_encode($goodsData);
//        dd($cateInfo);
        if(isset($_GET['jsoncallback'])){
            $function_name = $_GET['jsoncallback'];
            return $function_name ."(". $goodsData .")";
        }
        return json_encode([
                            'msg'=>'查询成功',
                            'code'=>200,
                            'goodsData'=>$goodsData,

        ]);

    }
//    api 分类接口查询'
    public function list()
    {
        //商品分类
        $cateInfo = category::where(['parent_id'=>0])->get()->toArray();
        $cateInfo = json_encode($cateInfo);
        if(isset($_GET['jsoncallback'])){
            $function_name = $_GET['jsoncallback'];
            return $function_name ."(". $cateInfo .")";
        }
        return json_encode([
            'msg'=>'查询成功',
            'code'=>200,
            'cateInfo'=>$cateInfo,

        ]);
    }
    //查询商品的详细的信息
    public function play(Request $request)
    {
        $num = $request->get('num');//中间件产生的参数 访问量
//        dd($num);
        $goods_id = $request->input('goods_id');
        //添加商品的访问量信息
        Goods::where(['goods_id'=>$goods_id])->update(['pv'=>$num]);
        //查询商品表基本信息
        $goodsData = Goods::where(['goods_id'=>$goods_id])->first()->toArray();
        //查询 商品属性的信息
        $goodsAttr = goods_attr::join('attribute','attribute.attr_id','=','goods_attr.attr_id')
            ->where(['goods_id'=>$goods_id])
            ->get()
            ->toArray();
        $specData=[];//可选规格属性
        $argsData=[];//不可选的参数
        foreach($goodsAttr as $key => $value){
            //找出可选参数和不可选参数
            if($value['attr_make'] == 1){
                //可选参数  先定义一个标识
                $attr_name = $value['attr_name'];
                $specData[$attr_name][] = $value;
            }else{
                //不可选参数
                $attr_name = $value['attr_name'];
                $argsData[$attr_name][] = $value;
            }
        }
//        echo "<pre>";
////        dump($specData);
//        dump($argsData);
        return json_encode([
                'msg'=>'查询成功',
                'goodsData'=>$goodsData,//商品基本信息
                'goods_Attr'=>$goodsAttr,//商品属性信息
                'specData'=>$specData,//可选属性参数
                'argsData'=>$argsData,//不可选的参数规格
        ]);
    }
    //查询分类下的所有商品信息
    public function cate(Request $request)
    {
        $cate_id = $request->input('cate_id');
        //查询所有的商品
        $goodsInfo = Goods::join('category','category.cate_id','=','goods.cate_id')
            ->select('goods.*','category.cate_name')
            ->get()
            ->toArray();
//        dd($goodsInfo);
        //查询大分类
        $cateInfo = category::where(['parent_id'=>0])->get()->toArray();
        return json_encode([
            'goodsInfo'=>$goodsInfo,
            'cateInfo'=>$cateInfo,
        ]);
    }
    public function cartAdd(Request $request)
    {
        $usertoken = $request->get('usertoken');
        $token = $request->get('token');

        //接收商品id 商品属性组合 token 和购买的商品数量
        $api_id = $usertoken->api_id;//用户id
        $goods_id = $request->goods_id;
//        $token = $request->token;
        $goods_attr_list = implode(',',$request->goods_attr_list);
        $cart_num = 1;
//        dd($goods_attr_list);die;
        //查询货品表 用于判断 库存有没有货
        $itemInfo = item::where(['goods_id'=>$goods_id,'goods_attr_list'=>$goods_attr_list])->first();
        //查询商品表
        $goodsInfo = Goods::where(['goods_id'=>$goods_id])->get()->toArray();
//        dd($goodsInfo);
        //仓库里的货存量
        $item_number = $itemInfo['item_number'];
       //查询 购物车表 有商品信息加1 无商品信息 新增
        $cartInfo = api_cart::where(['goods_id'=>$goods_id,'api_id'=>$api_id])->first();
//        dd($cartInfo);
//        if($itemInfo['item_number'] >=$cartInfo->cart_num){
//                echo "有货";
//        }else{
//                echo "没货啦";
//        }
        if(!empty($cartInfo)){
            //非空 购物车数量加1
            api_cart::where(['goods_id'=>$goods_id,'api_id'=>$api_id])->update(['cart_num'=>$cart_num+1]);
        }else{
            //为空 购物车新增
            api_cart::where(['goods_id'=>$goods_id,'api_id'=>$api_id])->insert([
                        'token'=>$token,
                        'api_id'=>$api_id,
                        'goods_id'=>$goods_id,
                        'cart_num'=>$cart_num,
                        'goods_attr_list'=>$goods_attr_list,
                        'goods_name'=>$goodsInfo[0]['goods_name'],
                        'goods_price'=>$goodsInfo[0]['goods_price'],
                        'goods_img'=>$goodsInfo[0]['goods_img'],
            ]);
        }
            return json_encode([
                'cartInfo'=>$cartInfo,
                'msg'=>'添加购物车成功',
                'code'=>200,
            ]);
    }
    //购物车展示接口
    public  function cartShow(Request $request)
    {
        $usertoken = $request->get('usertoken');
//        dd($usertoken);
        $api_id = $usertoken->api_id;
        //查询购物车表 商品属性表
        $cartInfo = api_cart::join('goods','api_cart.goods_id','=','goods.goods_id')
            ->where(['api_id'=>$api_id])
            ->get()->toArray();
//        dd($cartInfo);
        //查询出属性值的组合
        foreach($cartInfo as $key => $value){
            //将属性值的组合 分割成一维数组 用于where条件查询
            $goods_attr_list = explode(',',$value['goods_attr_list']);
            //查询属性值表
            $goodsAttrData = goods_attr::join('attribute','goods_attr.attr_id','=','attribute.attr_id')
                ->wherein('goods_attr_id',$goods_attr_list)
                ->get()
                ->toArray();
//            dd($goodsAttrData);
            //组装字符串 商品的属性:属性值
            $attr_list = ''; //存储商品的属性属性值
            $count_price = $value['goods_price'];//商品的真实价格
            foreach ($goodsAttrData as $k => $v){
                $attr_list .= $v['attr_name'].':'.$v['goods_attr_value'].',';
                $count_price = $v['goods_attr_price']; //商品属性的价格计算

            }
            //重新对商品的元素进行赋值
            $cartInfo[$key]['goods_attr_list'] =rtrim($attr_list,',');
            $cartInfo[$key]['count_price'] = $count_price;
        }
//        dd($cartInfo);

        return json_encode([
            'msg'=>'查询成功',
            'code'=>200,
            'cartInfo'=>$cartInfo,
        ]);
    }

}
