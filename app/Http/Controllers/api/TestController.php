<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Tools\Curl;
use Illuminate\Support\Facades\Cache;
use App\model\news;

class TestController extends Controller
{
    /**
     * 调用时事新闻接口
     */
   public function news()
   {
//      先读缓存 没存储
       $shuju = Cache::get('news');
       if ($shuju){
           return $shuju;
       }else{
           $url = 'http://api.avatardata.cn/ActNews/Query?key=f134c2c9bfef41a39deb0cc9ed6ac46c&keyword=北京';
           $dataInfo = Curl::get($url);
           $dataInfo = json_decode($dataInfo,1);
           //将数据存储到redis里面
           Cache::put('news',$dataInfo);
       }

   }

}
