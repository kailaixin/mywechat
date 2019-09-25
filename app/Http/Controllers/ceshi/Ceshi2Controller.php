<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Tools\Tools;

class Ceshi2Controller extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    //测试 任务调度
    public function ceshi()
    {
//        dd(56156);
        //获取用户列表接口
        $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_wechat_access_token().'&next_openid=';
//        dd($user_url);
        $req = file_get_contents($user_url);
        $req = json_decode($req,1);
//        dd($req);
        foreach($req['data']['openid'] as $v){
            //获取用户基本信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN';
            $user_re = file_get_contents($url);
            $user_info = json_decode($user_re,1);
//            dd($user_info);

                $list_openid = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$v])->first();
                if(empty($list_openid)){
                    //没有数据 就是未签到
                    DB::connection('1901')->table('wechat_openid')->where(['openid'=>$v])->insert([
                        'openid'=>$v,
                        'add_time'=>time(),
                    ]);
//           //发送模板消息接口
                    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
                    $data = [
                        'touser'=>'oIhHHwSoKsFZacgx0NZ9B9RV6xmg',
                        'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                        "url"=>"http://weixin.qq.com/download",
                        'data'=>[
                            'keyword1'=>[
                                'value'=>$user_info['nickname'],
                                'color'=>'',
                            ],
                            'keyword2'=>[
                                'value'=>'未签到',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'0',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'',
                                'color'=>'',
                            ],
                        ],
                    ];

                    $res = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
                }else{
//                    有数据的
                   //今天的时间
                    $today = date('Y-m-d',time());
                    if($list_openid->sign_date == $today ){ //判断是否签到
                        //发送模板消息接口
                        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
                        $data = [
                            'touser'=>'oIhHHwSoKsFZacgx0NZ9B9RV6xmg',
                            'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                            "url"=>"http://weixin.qq.com/download",
                            'data'=>[
                                'keyword1'=>[
                                    'value'=>$user_info['nickname'],
                                    'color'=>'',
                                ],
                                'keyword2'=>[
                                    'value'=>'已签到',
                                    'color'=>'',
                                ],
                                'keyword3'=>[
                                    'value'=>'0',
                                    'color'=>'',
                                ],
                                'keyword3'=>[
                                    'value'=>'',
                                    'color'=>'',
                                ],
                            ],
                        ];

                        $res = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));

                    }else{//未签到的
                        //发送模板消息接口
                        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
                        $data = [
                            'touser'=>'oIhHHwSoKsFZacgx0NZ9B9RV6xmg',
                            'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                            "url"=>"http://weixin.qq.com/download",
                            'data'=>[
                                'keyword1'=>[
                                    'value'=>$user_info['nickname'],
                                    'color'=>'',
                                ],
                                'keyword2'=>[
                                    'value'=>'未签到',
                                    'color'=>'',
                                ],
                                'keyword3'=>[
                                    'value'=>'0',
                                    'color'=>'',
                                ],
                                'keyword3'=>[
                                    'value'=>'',
                                    'color'=>'',
                                ],
                            ],
                        ];

                        $res = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
                        dd($res);
                    }
                }
        }

    }
   //测试2
    public function ceshi2()
    {
        $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_wechat_access_token().'&next_openid=';
        $req = file_get_contents($user_url);
//        $req = json_decode($req,1);
//        dd($req);
        $this->tools->redis->set('info5',$req);
       $req = $this->tools->redis->get('info5');
      $req = json_decode($req,1);

       foreach($req['data']['openid'] as $v){
//           dd($v);
           $arr=[];
           //获取用户基本信息
           $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN';
           $re = file_get_contents($url);
           $arr[]=$re;



       }
//        dd($arr);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
        $data = [
            'touser'=>$re['openid'],
            'template_id'=>'JXvmiKOvSDGUoCD2Rp-TopksIdap80AiJOFG6bO0g7g',
            'data'=>[
                'first'=>[
                    'value'=>'尊敬的'.$re['nickname'].'用户您好，目前公司开展签到送积分兑换活动，详情进入公众号查看',
                ]
            ],

        ];
        dd(json_encode($data,JSON_UNESCAPED_UNICODE));
        $req = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($req);
    }

    //测试3 查询积分 和签到的代码
    public function event()
    {
        //签到和查询积分逻辑
        $xml_arr = [];
        if ($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'CLICK') {
            if ($xml_arr['EventKey'] == '2') {//操作签到
                $today = date('Y-m-d', time());//当天日期
                $prev_day = date('Y-m-d', strtotime('-1 days'));//前一天日期
                $openid_info = DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->first();
//            dd($openid_info);
                if (empty('openid_info')) {
                    DB::conncetion('1901')->table('wechat_openid')->insert([
                        'openid' => $xml_arr['FromUserName'],
                        'add_time' => time(),
                    ]);
                }
                $openid_info = DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->first();
                if ($openid_info->sign_date == $today) {
                    //已签到
                    $message = '今天您已经签到了';
                    $xml_str = '<xml><ToUserName><![CDATA[' . $xml_arr['FromUserName'] . ']]></ToUserName><FromUserName><![CDATA[' . $xml_arr['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                    echo $xml_str;
                } else {
                    //未签到  签到加积分
                    if ($prev_day == $openid_info->sign_date) { //获取
                        //连续签到 5天一轮换
                        if ($openid_info->sign_day >= 5) {
//                            dd(1231);
                            DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->update([
                                'sign_day' => 1,
                                'jifen' => $openid_info->jifen + 5,
                                'sign_date' => $today,
                            ]);
                        } else {
                            DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->update([
                                'sign_day' => $openid_info->sign_day + 1,
                                'jifen' => $openid_info->jifen + 5 * ($openid_info->sign_day + 1),
                                'sign_date' => $today,
                            ]);
                        }
//
                    } else {
                        //非连续   加积分后  连续天数变1
                        DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->update([
                            'sign_day' => 1,
                            'jifen' => $openid_info->jifen + 5,
                            'sign_date' => $today,
                        ]);
                    }
                    $message = '签到成功';
                    $xml_str = '<xml><ToUserName><![CDATA[' . $xml_arr['FromUserName'] . ']]></ToUserName><FromUserName><![CDATA[' . $xml_arr['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                    echo $xml_str;

                }
            }
            if ($xml_arr['EventKey'] == '200') {
                //查积分
                $openid_info = DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->first();
//            dd($openid_info);
                if (empty($openid_info)) {
                    //没有数据的话存入
                    DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->insert([
                        'openid' => $xml_arr['FromUserName'],
                        'add_time' => time(),
                    ]);
                    $message = '积分:0';
                    $xml_str = '<xml><ToUserName><![CDATA[' . $xml_arr['FromUserName'] . ']]></ToUserName><FromUserName><![CDATA[' . $xml_arr['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                    echo $xml_str;
                } else {
                    $message = '积分:' . $openid_info->jifen;
                    $xml_str = '<xml><ToUserName><![CDATA[' . $xml_arr['FromUserName'] . ']]></ToUserName><FromUserName><![CDATA[' . $xml_arr['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                    echo $xml_str;
                }
            }
        }

//            关注公众号的逻辑
        if ($xml_arr['MsgType'] == 'event' && $xml_arr['Event'] == 'subscribe') {
            //拿到用户基本信息的openid
            $user_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $this->tools->get_wechat_access_token() . '&openid=' . $xml_arr['FromUserName'] . '&lang=zh_CN';
            $res = file_get_contents($user_url);
            $res = json_decode($res, 1);
//            dd($res);
            //将关注的用户存入数据库中
            $user_info = DB::connection('1901')->table('wechat_openid')->where(['openid' => $xml_arr['FromUserName']])->first();
//            dd($user_info);
            if (empty($user_info)) {
                //m没有数据存入
                DB::connection('1901')->table('wechat_openid')->insert([
                    'openid' => $xml_arr['FromUserName'],
                    'add_time' => time(),
                ]);
            }

        }
    }

    //任务调度 签到 查询积分代码
   public function indes()
{
    $schedule=[];
    $schedule->call(function(Tools $tools){
        //功能 业务逻辑
        \Log::Info('自动执行任务');
//            dd(56156);
        //获取用户列表接口
        $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$tools->get_wechat_access_token().'&next_openid=';
//        dd($user_url);
        $req = file_get_contents($user_url);
        $req = json_decode($req,1);
//        dd($req);
        foreach($req['data']['openid'] as $v){
            //获取用户基本信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$tools->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN';
            $user_re = file_get_contents($url);
            $user_info = json_decode($user_re,1);
//            dd($user_info);

            $list_openid = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$v])->first();
            if(empty($list_openid)){
                //没有数据 就是未签到
                DB::connection('1901')->table('wechat_openid')->where(['openid'=>$v])->insert([
                    'openid'=>$v,
                    'add_time'=>time(),
                ]);
//           //发送模板消息接口
                $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$tools->get_wechat_access_token();
//        dd($url);
                $data = [
                    'touser'=>$user_info['openid'],
                    'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                    "url"=>"http://weixin.qq.com/download",
                    'data'=>[
                        'keyword1'=>[
                            'value'=>$user_info['nickname'],
                            'color'=>'',
                        ],
                        'keyword2'=>[
                            'value'=>'未签到',
                            'color'=>'',
                        ],
                        'keyword3'=>[
                            'value'=>'0',
                            'color'=>'',
                        ],
                        'keyword3'=>[
                            'value'=>'',
                            'color'=>'',
                        ],
                    ],
                ];

                $res = $tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
            }else{
//                    有数据的
                //今天的时间
                $today = date('Y-m-d',time());
                if($list_openid->sign_date == $today ){ //判断是否签到
                    //发送模板消息接口
                    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$tools->get_wechat_access_token();
//        dd($url);
                    $data = [
                        'touser'=>$user_info['openid'],
                        'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                        "url"=>"http://weixin.qq.com/download",
                        'data'=>[
                            'keyword1'=>[
                                'value'=>$user_info['nickname'],
                                'color'=>'',
                            ],
                            'keyword2'=>[
                                'value'=>'已签到',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'0',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'',
                                'color'=>'',
                            ],
                        ],
                    ];

                    $res = $tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));

                }else{//未签到的
                    //发送模板消息接口
                    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$tools->get_wechat_access_token();
//        dd($url);
                    $data = [
                        'touser'=>$user_info['openid'],
                        'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                        "url"=>"http://weixin.qq.com/download",
                        'data'=>[
                            'keyword1'=>[
                                'value'=>$user_info['nickname'],
                                'color'=>'',
                            ],
                            'keyword2'=>[
                                'value'=>'未签到',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'0',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'',
                                'color'=>'',
                            ],
                        ],
                    ];

                    $res = $tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//                        dd($res);
                }
            }
        }


    })->yearly();
}

    //添加自定义菜单
    public function create_menu()
    {
        dd(date('Y-m-d H:i:s' ,1569314229));
        return view('ceshi/create_menu');
    }
    //自定义菜单处理
    public function create_menu_do()
    {
        //接收
        $post = request()->except('_token');
//        dd($post);
        $post['button_type'] = empty($post['name2'])?1:2;
//        dd($post);
//        入库
        $req = DB::connection('1901')->table('wechat_menu')->insertGetId($post);
//        dd($req);
        if(!$req){
            dd('添加失败');
        }
        //根据数据库表数据翻译成菜单结构
        $this->menu();
    }
    /***
     * 数据库菜单表格数据
     */
    /** 数据库菜单表格数据*/
    public function menu()
    {
        $data = [];
//         $event_arr = [1=>'click',2=>'view'];
        $menu_list = DB::connection('1901')->table('wechat_menu')->select(['name1'])->groupBy('name1')->get(); // 获取一级菜单
//        dd($menu_list);
        foreach($menu_list as $vv) {
            $menu_info = DB::table('wechat_menu')->where(['name1'=>$vv->name1])->get(); // 获取一级菜单下所有 三维数组
//             dd($menu_info);
            $menu = [];
            foreach ($menu_info as $v) {
                $menu[] = (array)$v; // 获取一级菜单下所有中的一个 二维数组

            }
//             dd($menu);
            $arr = [];
            foreach ($menu as $v) { // 获取一级菜单下所有中的一个 一维数组
                if ($v['button_type'] == 1) { // 普通一级菜单
                    if ($v['type'] == 1) { // click
                        $arr = [
                            'type' => 'click',
                            'name' => $v['name1'],
                            'key'  => $v['event_value']
                        ];
                    }elseif ($v['type'] == 2) { // view
                        $arr = [
                            'type' => 'view',
                            'name' => $v['name1'],
                            'url'  => $v['event_value']
                        ];
                    }
                }elseif ($v['button_type'] == 2) { // 带有二级菜单的一级菜单
                    $arr['name'] = $v['name1'];
//                     dd($arr);
                    if ($v['type'] == 1) { // click
                        $button_arr = [
                            'type' => 'click',
                            'name' => $v['name2'],
                            'key'  => $v['event_value']
                        ];
                    }elseif ($v['type'] == 2) { // view
                        $button_arr = [
                            'type' => 'view',
                            'name' => $v['name2'],
                            'url'  => $v['event_value']
                        ];
                    }
                    $arr['sub_button'][] = $button_arr;
                }
            }

//             dd($v);
            $data['button'][] = $arr;
        }
//         dd($arr);

        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->tools->get_wechat_access_token();
//         dd($url);
        $res = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//         dd($res);
        $result = json_decode($res,1);
        dd($result);
    }

}
