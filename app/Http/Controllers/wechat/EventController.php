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
        $wechat_log_path = storage_path('/logs/wechat/'.date("Y-m-d").'wx.log');  // 生成日志文件
        file_put_contents($wechat_log_path,"<<<<<<<<<<<<<<".date('Y-m-d H:i:s',time())."<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_path,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_path,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);

//        dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        $xml_arr = (array)$xml_obj;
//        dd($xml_arr);
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
//        echo $_GET['echostr'];
        //业务逻辑部分
//

            //关注公众号 回复消息逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){//获取用户的基本信息
//            dd(132);
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$xml_arr['FromUserName'].'&lang=zh_CN';
            $req = file_get_contents($url);
            $req = json_decode($req,1);
//            dd($req);
            $user_info = DB::connection('1901')->table('user_weixin')->where(['openid'=>$xml_arr['FromUserName']])->first();
//            dd($user_info);
            if(empty($user_info)){//如果为空的话 就是没有数据 添加到数据库
                $re = DB::connection('1901')->table('user_weixin')->insert([
                    'openid'=>$xml_arr['FromUserName'],
                    'name'=>$req['nickname'],
                    'sex'=>$req['sex'],
                    'chengshi'=>$req['city'],
                    'subscribe_time'=>$req['subscribe_time'],
                ]);
//                dd($re);
               if($re){
                   $message = '您好'.$req['nickname'].'：当前时间为 '.date('Y-m-d H:i:s',$req['subscribe_time']).'';
                   $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                   echo $xml_str;
               }

            }else{
                $message ='欢迎回来'.$user_info->name.'：当前时间为 '.date('Y-m-d H:i:s',$user_info->subscribe_time).'';
                $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
            }

        }
        $message = '您好同学，感谢您的关注';
        $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
        echo $xml_str;
        }


}
