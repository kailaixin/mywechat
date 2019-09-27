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
//             管理课程逻辑部分
            if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'VIEW'){
                   if ($xml_arr['EventKey'] == 'guanli'){//管理课程页面
                       $url_guanli='https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$this->tools->get_wechat_access_token();
                      $req = file_get_contents($url_guanli);
                      $req = json_decode($req,1);
//                      dd($req);
                   }
            }
//            课程查看逻辑部分
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'CLICK'){
                  if ($xml_arr['EventKey'] == 'chakan'){//课程查看部分
                        //判断用户是否选择过课程
//                      dd(1231);
                    $openid_list = DB::connection('1901')->table('kecheng')->where(['openid'=>$xml_arr['FromUserName']])->orderby('ke_id','desc')->first();
//                    dd($openid_list);
                    if($openid_list){
//                        有数据就是已经添加过课程
                        //获取用户列表
                        $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_wechat_access_token().'&next_openid=';
                        $req = file_get_contents($user_url);
                        $req = json_decode($req,1);

                        foreach ($req['data']['openid'] as $v){
                            //获取用户基本信息
                            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN';
                            $user_re = file_get_contents($url);
                            $user_info = json_decode($user_re,1);
                            //把没有选课程的用户添加到数据库
                            $list_openid = DB::connection('1901')->table('kecheng')->where(['openid'=>$v])->first();
//                            dd($list_openid);
                           if(empty($list_openid)){
                               //没有数据 就是没有选课程
                               DB::connection('1901')->table('kecheng')->where(['openid'=>$v])->insert([
                                   'openid'=>$v,
                                   'add_time'=>date('Y-m-d',time()),
                               ]);
                               //发送模板消息
                               $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
                               $data = [
                                   'touser'=>$user_info['openid'],
                                   'template_id'=>'z7ojM8DzzSaV6fqbO-x11QA05itn7P59FcvYcqmP-ME',
                                   "url"=>"http://weixin.qq.com/download",
                                   'data'=>[
                                       'keyword'=>[
                                           'value'=>$user_info['nickname'],
                                           'color'=>'',
                                       ],

                                   ],
                               ];
//                               dd($data);
                               $ree = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//                           dd($ree);

                           }else{
                               //发送模板消息
                               $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
                               $data = [
                                   'touser'=>$user_info['openid'],
                                   'template_id'=>'T-566spD9QOBlup9690Y54JfOyxbXmWGcr8TlesOtbs',
                                   "url"=>"http://weixin.qq.com/download",
                                   'data'=>[
                                       'keyword'=>[
                                           'value'=>$user_info['nickname'],
                                           'color'=>'',
                                       ],
                                       'keyword1'=>[
                                           'value'=>$list_openid->php,
                                           'color'=>'',
                                       ],
                                       'keyword2'=>[
                                           'value'=>$list_openid->yuwen,
                                           'color'=>'',
                                       ],
                                       'keyword3'=>[
                                           'value'=>$list_openid->shuxue,
                                           'color'=>'',
                                       ],
                                       'keyword4'=>[
                                           'value'=>$list_openid->yingyu,
                                           'color'=>'',
                                       ],
                                   ],
                               ];
                               $req = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//                               dd($req);
                           }

                        }


                    }else{
//                        否则就是没有添加过数据
                        $message = '请先选择课程';
                        $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                        echo $xml_str;
                    }
                  }
        }


            //关注公众号 回复消息逻辑
        if($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe'){//获取用户的基本信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$xml_arr['FromUserName'].'&lang=zh_CN';
            $req = file_get_contents($url);
            $req = json_decode($req,1);
//            dd($req);
            $user_info = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$xml_arr['FromUserName']])->first();
//            dd($user_info);
            if(empty($user_info)){//如果为空的话添加数据库
                DB::connection('1901')->table('wechat_openid')->insert([
                    'openid'=>$xml_arr['FromUserName'],
                    'add_time'=>time(),
                ]);
            }
            $message = '欢迎'.$req['nickname'].'同学进入选课系统';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;
        }

        }


}
