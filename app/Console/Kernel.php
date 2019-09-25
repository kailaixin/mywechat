<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Tools\Tools;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */



    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
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


        })->everyMinute();


        $schedule->call(function(Tools $tools){
//            123123;
//            \Log::Info('13215613');
            $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$tools->get_wechat_access_token();
//        dd($url);
            $data = [
                "filter"=>[
                    "is_to_all"=>false,
                    'tag_id'=>'108',
                ],
                "text"=>[
                    'content'=>'time()'
                ],
                'msgtype'=>'text',
            ];
//        dd($data);
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
//        dd($data);
            $req = $tools->curl_post($url,$data);
//        dd($req);
//            \Log::Info('13215613');
        })->yearly();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
