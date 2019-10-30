<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\Goods;
use Illuminate\Support\Facades\Cache;
use App\Tools\Curl;
use App\model\news;
use App\model\api_user;

class Test1Controller extends Controller
{
    //添加商品接口
    public function add(Request $request)
    {
//        接收
        $name = $request->input('name');
        $price = $request->input('price');
        //验证
        if(empty($name) || empty($price)){
            dd('请填写商品和价格');
        }

        //判断文件是否存在
        if ($request->hasFile('goods_img')) {
            $file = $request->file('goods_img');
        };
        //验证文件是否成功上传
        if ($request->file('goods_img')->isValid()) {
            $img = $request->goods_img->store('images');
        }
//      入库
       $res = Goods::insert([
            'goods_name'=>$name,
           'goods_price'=>$price,
           'goods_img'=>$img,
        ]);
      if($res){
          return json_encode(['msg'=>'添加成功','code'=>200]);die;
      }else{
          return json_encode(['msg'=>'添加失败','code'=>201]);die;
      }
    }

    //接口 展示页面
    public function show()
    {
        $data = Goods::paginate(3);
//        dd($data);
        return json_encode(['data'=>$data,'msg'=>'查询成功','code'=>200]);
    }
    //调用天气接口
    public function weather(Request $request)
    {
        $city = $request->input('city');
//        dd($city);
        if(!isset($city)){
            $city='北京';
        };

        //键名拼接城市名
        $cache_key = 'weather_data_'.$city;
//        dd($cache_key);
        //      有缓存 读取缓存
        $weather_data = Cache::get($cache_key);
//        dd($weather_data);
        if(empty($weather_data)){
//            echo "ok";
            $url="http://api.k780.com/?app=weather.future&weaid={$city}&&appkey=45880&sign=0c4e56cddbb7aa7764221cf64d832009&format=json";
//        dd($url);
            $weather_data = file_get_contents($url);
//        dd($data);
            //设置缓存时间 当天24点的时间  strtotime()//转换成时间戳
            $time1 = date('Y-m-d');
            $time24 = strtotime($time1)+86400;
            //获取当前的时间
           $Cache_time = $time24 - time();
            Cache::put($weather_data, $weather_data, $Cache_time);
        }
        return $weather_data;
    }
    //////////////////////////////////////////////////////////////////////////
    /**
     * 月考测试
     */
    public function test(Request $request)
    {
        $url1 = "http://api.avatardata.cn/ActNews/LookUp?key=f134c2c9bfef41a39deb0cc9ed6ac46c";
        $hotData = file_get_contents($url1);
        $hotData = json_decode($hotData,1);
//        dd($rq);
        $key_hotData =[];//新闻热点
       for ($i=0;$i <=9;$i++){
        $key_hotData[] = $hotData['result'][$i];
       }

       foreach($key_hotData as $k => $v){
           //调用接口的地址
           $url = "http://api.avatardata.cn/ActNews/Query?key=f134c2c9bfef41a39deb0cc9ed6ac46c&keyword={$v}";
           //调用curl_get方法 处理
           $dataInfo= Curl::get($url);
           $dataInfo = json_decode($dataInfo,1);
//        dd($dataInfo);
//        /信息入库 返回的数据部位空的情况下
           if (!empty($dataInfo['result'])){
               foreach($dataInfo['result'] as $key => $value){
                   $newData = news::where(['new_title'=>$value['title']])->first();
                   //有数据的话数据库肯定重复
                   if (empty($newData)){
                       news::create([
                           'new_title'=>$value['title'],
                           'new_content'=>$value['content'],
                           'img'=>$value['img'],
                           'pdate_src'=>$value['pdate_src'],
//                           'img_length'=>$value['img_length'],
                       ]);
                   }
           }
           }


       }


    }

    /**
     * 注册页面处理
     */
    public function reg_do(Request $request)
    {
//        接值
        $name = $request->input('username');
        $pwd = $request->input('userpwd');
//        验证
//        入库
        $res = api_user::create([
                'api_name'=>$name,
                'api_pwd'=>$pwd,
        ]);
        if ($res){
            return "<script>alert('注册成功');location.href='test1/login'</script>";
        }else{
            return "<script>alert('注册失败');location.href='test1/reg';</script>";
        }

    }
    /**
     *  登录页面11
     */
    public function login(Request $request)
    {
      $name = $request->input('name');
      $pwd = $request->input('pwd');
      dump($name);
      dd($pwd);
    }
}
