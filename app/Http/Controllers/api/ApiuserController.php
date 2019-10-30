<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\api_user;
class ApiuserController extends Controller
{
    public function log(Request $request)
    {
        //接收用户名 和密码
        $username = $request->input('username');
        $password = $request->input('userpwd');
        //查询数据库
        $userInfo = api_user::where(['api_name'=>$username,'api_pwd'=>$password])->first();
       if(!$userInfo){
           dd('用户名或密码不正确');
       }
//        生成token令牌 和过期时间
        $token = md5($userInfo['api_id'].time());
       $expire_time = time()+7200;
       //修改数据库
        $userInfo->api_token = $token;
        $userInfo->expire_time = $expire_time;
        $userInfo->save();
//      返回给客户端
        return json_encode(['code'=>200,'msg'=>'登录成功','token'=>$token]);
    }
    public function getuser()
    {
        //接收token
        $token = request()->input('token');
        if (empty($token)){
            //如果token不存在给出提示
            return json_encode(['code'=>201,'msg'=>'请先登录']);
        }
        //检验 token是否正确
        $usertoken = api_user::where(['api_token'=>$token])->first();
        if(empty($usertoken)){
            return json_encode(['code'=>202,'msg'=>'请先登录']);
        }
        //检验token是否已过有效期
        if (time() > $usertoken['expire_time'])
        {
            return json_encode(['code'=>203,'msg'=>'请先登录']);
        }
        //延长token的有效期
        api_user::where(['api_token'=>$token])->update(['expire_time'=>time()+7200]);
    }
}
