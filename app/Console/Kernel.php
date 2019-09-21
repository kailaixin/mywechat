<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
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
        $schedule->call(function(){
            DB::table('user_info')->insert(['user_name'=>'琉璃仙','user_pwd'=>md5('liulixian'),'headimg'=>'z1EaUeLedVB77Wy0hyK0j1kNqUdBdwhKWYY6FyBB.png','create_time'=>time()]);
        })->cron('* * * * *');
        $schedule->call(function(){
            $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->tools->get_wechat_access_token().'';
//        dd($url);
            $data = [
                "filter"=>[
                    "is_to_all"=>false,
                    'tag_id'=>'108',
                ],
                "text"=>[
                    'content'=>'爱你哦'
                ],
                'msgtype'=>'text',
            ];
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
//        dd($data);
            $req = $this->tools->curl_post($url,$data);
            $req = json_decode($req,1);
//        dd($req);
        })->daily();
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
