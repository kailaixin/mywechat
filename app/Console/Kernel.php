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
