<?php

namespace App\Http\Middleware;

use Closure;
use PhpParser\Node\Stmt\DeclareDeclare;
use Illuminate\Support\Facades\Cache;

class apihead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //需要在使用的方法加上跨域请求           
        header("Access-Control-Allow-Origin:*");
        header('Access-Control-Allow-Methods:POST');
        header('Access-Control-Allow-Headers:x-requested-with, content-type');
        //接口防刷处理
        // 根据ip 做防刷处理 获取到ip
        $ip = $_SERVER['REMOTE_ADDR'];
        //记录当前ip 一分钟内访问了多少次接口 存在缓存内
        $cache_name = "pass_time_".$ip;
        //先读缓存 没有访问次数的话 赋值0 有访问次数累加
        $num = Cache::get($cache_name);
        if (!$num){
            $num = 0;
        }
//        if ($num >= 10){
//           echo json_encode([
//               'code'=>201,
//               'msg'=>'访问频繁 请稍后再试',
//           ]);die;
//        }
        $num += 1;
//        echo $num;die;
        Cache::put($cache_name,$num);//存入缓存内

        $mid_params = ['num'=>$num];
        $request->attributes->add($mid_params);//添加参数
        return $next($request);
    }

    /**
     *
     * 接口的防刷处理
     * @param $request
     * @param Closure $next
     * @return mixed
     *
     */
    public function brush($request, Closure $next)
    {

        return $next($request);
    }
}
