<?php

namespace App\Http\Controllers\wechat;

use App\Tools\Tools;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class JSSDKController extends Controller
{
    public $tools;

    public function __construct(Tools $tools)
    {
      $this->tools= $tools;

    }

    public function sdk_list()
    {
        $url ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//        dd($url);
        $jsapi_ticket = $this->tools->get_wechat_ticket();
//        dd($jsapi_ticket);
        $timestamp = time();
        $nonceStr = rand(1000,9999).'suibian';
        $signature ='';
       $sign_str =  'jsapi_ticket='.$jsapi_ticket.'&nonceStr='.$nonceStr.'&timestamp='.$timestamp.'&url='.$url;
//       dd($sign_str);
        $signature = sha1($sign_str);
//        dd($signature);
        return view('sdk/sdk_list',compact('nonceStr','signature','timestamp'));
    }

}
