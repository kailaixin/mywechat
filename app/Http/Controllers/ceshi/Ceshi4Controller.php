<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\api_user;

class Ceshi4Controller extends Controller
{
    /**
     * 注册
     */
    public function reg()
    {
        return view('ceshi/ceshi4/reg');
    }

    /**
     * 注册处理
     */
    public function reg_do(Request $request)
    {
//        接收注册信息
      $data =  $request->except('_token');
        $appID = '1901'.time().rand(0000,9999);
        $appsecret = md5(time().rand(0000,999));
//        入库
        $res = api_user::insert([
            'api_name'=>$data['username'],
            'api_pwd'=>$data['userpwd'],
            'appid'=>$appID,
            'appsecret'=>$appsecret,
        ]);
//        dd($res);
        if($res){
            echo "<script>alert('注册成功');location.href='login'</script>>";
        }else{
            echo "<script>alert('注册失败');location.href='reg'</script>>";
        }
    }

    /**
     * 登录页面
     */
    public function login()
    {
        return view('ceshi/ceshi4/login');
    }

    /**
     * 登录处理页面
     */
    public function login_do(Request $request)
    {
        $api_name = $request->input('username');
        $api_pwd = $request->input('userpwd');
        //查询数据库
       $userData = api_user::where(['api_name'=>$api_name,'api_pwd'=>$api_pwd])->first();
        if (!$userData){
            dd("用户名或密码错误.....请重新输入");
        }
        //生成token 和过期时间
        $token ='1902a'.md5(time().rand(0000,9999));//新生成的token
//        dd($token);
        $exp_time = time()+7200;
        //修改数据库
        $userData->api_token = $token;
        $userData->expire_time = $exp_time;
        $userData->save();
        return json_encode([
            'msg'=>'登录成功',
            'code'=>200,
            'token'=>$token,
            'userData'=>$userData,
        ]);
    }
    public function admin(Request $request)
    {
        $userData=request()->get('usertoken')->toArray();
//        dd($userData);die;
        return view('ceshi/ceshi4/admin',[
            'userData'=>$userData,
        ]);
    }
}
