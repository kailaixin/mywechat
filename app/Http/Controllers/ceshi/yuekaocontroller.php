<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Tools\Tools;
use App\Http\Controllers\Controller;

class yuekaocontroller extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
       $this->tools = $tools;
    }

    //获取access_token
    public function get_token()
    {
        //加入缓存
        $access_token_key = 'wechat_access_token';
        if($this->tools->redis->exists($access_token_key)){
            //存在
            return $this->tools->redis->get($access_token_key);
        }else{
            //不存在
            $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'');
//            dd($result);
            $re = json_decode($result,1);
//            dd($re);
            $this->tools->redis->set($access_token_key,$re['access_token'],$re['expires_in']);  //加入缓存
            return $re['access_token'];
        }
    }
}
