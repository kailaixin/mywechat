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
                //签到和查询积分逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'CLICK'){
            if ($xml_arr['EventKey'] == '2'){//操作签到
                $today = date('Y-m-d',time());//当天日期
                $prev_day = date('Y-m-d',strtotime('-1 days'));//前一天日期
            $openid_info = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->first();
//            dd($openid_info);
                if(empty('openid_info')){
                    DB::conncetion('1901')->table('wechat_openid')->insert([
                        'openid'=>$xml_arr['FromUserName'],
                        'add_time'=>time(),
                    ]);
                }
                $openid_info = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->first();
                if($openid_info->sign_date == $today){
                    //已签到
                $message = '今天您已经签到了';
                $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
                }else{
                    //未签到  签到加积分
                    if($prev_day == $openid_info->sign_day){ //如果昨天的日期已经在数据库了,连续签到
                        //连续签到 5天一轮换
                        if($openid_info->sign_day >= 5){
//                            dd(1231);
                            DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->update([
                                'sign_day'=>$openid_info->sign_day + 1,
                                'jifen'=>$openid_info->jifen + 5,
                                'sign_date'=>$today,
                            ]);
                        }else{
                            DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->update([
                                'sign_day'=>$openid_info->sign_day + 1,
                                'jifen'=>$openid_info->jifen + 5 *($openid_info->sign_day + 1),
                                'sign_date'=>$today,
                            ]);
                        }
//
                    }else{
                        //非连续   加积分后  连续天数变1
                       DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->update([
                                'sign_day'=>1,
                                'jifen'=>$openid_info->jifen + 5,
                                'sign_date'=>$today,
                        ]);
                    }
                    $message = '签到成功';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;

                }
            }
            if ($xml_arr['EventKey'] == '200'){
                //查积分
                $openid_info = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->first();
//            dd($openid_info);
                if(empty($openid_info)){
                    //没有数据的话存入
                    DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->insert([
                        'openid'=>$xml_arr['FromUserName'],
                        'add_time'=>time(),
                    ]);
                    $message = '积分:0';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }else{
                    $message = '积分:'.$openid_info->jifen;
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }
            }
        }

//            关注公众号的逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){
            //拿到用户基本信息的openid
            $user_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$xml_arr['FromUserName'].'&lang=zh_CN';
            $res = file_get_contents($user_url);
            $res = json_decode($res,1);
//            dd($res);
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
