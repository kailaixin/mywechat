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
    public function add()
    {
        return view('ceshi/add');
    }


    ////////////////////////////////////
    public function jiekou ()
    {
        $url = 'http://wym.yingge.fun/api/user/test';

        $name = "开来鑫";
        $age = "18";
        $mobile = "15500805364";
        $rand = rand(0000,9999);
        $time = time();
        $sign = sha1("1902"."age={$age}&mobile={$mobile}&name={$name}&rand={$rand}");
//        dd($sign);
        $url .= "?name={$name}&age={$age}&mobile={$mobile}&rand={$rand}&sign={$sign}";
        $res = file_get_contents($url);
        dump($res);
    }
    public function curl_post($url,$data)
    {
//        dd(13213);
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,true);  //发送post
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $data = curl_exec($curl);
//        dd($data);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        curl_close($curl);
        return $data;
    }

}
