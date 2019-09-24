<?php

namespace App\Http\Controllers\wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
use DB;

class EventController extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }
    public function event()
    {
//        echo $_GET['echostr'];die();
//        dd($_POST);
        $xml_string = file_get_contents('php://input'); // 获取微信发过来的xml数据
//        dd($xml_string);
        $wechat_log_path = storage_path('/logs/wechat/'.date("Y-m-d").'.log');  // 生成日志文件
        file_put_contents($wechat_log_path,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_path,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_path,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);

//        dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        $xml_arr = (array)$xml_obj;
//        dd($xml_arr);
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
//        echo $_GET['echostr'];
        //业务逻辑部分
                //签到逻辑
//        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'CLICK'){
//
//        }
//            关注公众号的逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){
            //拿到用户基本信息的openid
            $user_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$xml_arr['FromUserName'].'&lang=zh_CN';
            $res = file_get_contents($user_url);
            $res = json_decode($res,1);
            //将关注的用户存入数据库中
            $user_info = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->first();
//            dd($user_info);
            if(empty($user_info)){
                //m没有数据存入
                DB::connection('1901')->table('wechat_openid')->insert([
                        'openid'=>$xml_arr['FromUserName'],
                        'add_time'=>time(),
                ]);
            }

            $message = '欢迎'.$res['nickname'].'同学，感谢您的关注';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;
        }







    }
}
