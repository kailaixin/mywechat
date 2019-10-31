<?php

namespace App\Http\Middleware;

use Closure;
use App\model\api_user;

class token
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
        //接收token
//        echo 1;die;
//        $token = request()->input('token');
        $token=$_COOKIE['token'];
//            dd($token);
        if (empty($token)){
            //如果token不存在给出提示
            echo json_encode(['code'=>201,'msg'=>'token不存在']);die;
        }
        //检验 token是否正确
        $usertoken = api_user::where(['api_token'=>$token])->first();
//        dd($usertoken);
        if(empty($usertoken)){
            echo json_encode(['code'=>202,'msg'=>'token不正确']);die;
        }
        //检验token是否已过有效期
        if (time() > $usertoken['expire_time'])
        {
            echo json_encode(['code'=>203,'msg'=>'token已过期']);die;
        }
        //延长token的有效期
        api_user::where(['api_token'=>$token])->update(['expire_time'=>time()+7200]);

        $mid_params = ['usertoken'=>$usertoken];
        $request->attributes->add($mid_params);//添加参数
        return $next($request);//进行下一步(即传递给控制器)
    }
}
