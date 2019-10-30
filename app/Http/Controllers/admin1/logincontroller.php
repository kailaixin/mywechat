<?php

namespace App\Http\Controllers\admin1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\model\Curl;
use App\Tools\Tools;
use phpDocumentor\Reflection\Location;

class logincontroller extends Controller
{
    public $tools;
    public $curl;
    public function __construct(Tools $tools,Curl $curl)
    {
        $this->tools = $tools;
        $this->curl = $curl;
    }

    /*
     * 登录页
     * */
    public function login()
    {
        $code = session('user_code');
        var_dump($code);
        return  view('login1/login');
    }
    /*
     * 登录页处理
     *
     * */
    public function login_do()
    {
        //接收
        $post = request()->except('_token');
        $post['create_time'] = time();
        //查询数据库
        $adminData = DB::table('user_info')->where(['user_name'=>$post['user_name']])->first();
//        dd($adminData);
        if($adminData->user_name != $post['user_name']){
            dd('用户名不存在');
        }
       if($adminData->user_pwd != $post['user_pwd']){
           dd('密码不正确');
       }
       if($post['user_code'] !=session('user_code') ){
           dd('验证码不正确');
       }else{
           echo '登录成功';
           session(['adminData'=>$adminData]);
           header('Location: http://www.1902.com/admin1/index');
       }
    }
    /*
     * 发送微信验证码
     * */
    public  function code1()
    {
//接收
        $post = request()->except('_token');
        $post['create_time'] = time();
//        dd($post);
        //查询数据库
        $adminData = DB::table('user_info')->where(['user_name'=>$post['user_name']])->first();
//        dd($adminData);

        $openid = $adminData->openid;
        //发送验证码 4位 或6位
        $code = rand(0000,9999);
//        dd($code);
        $time=date('Y-m-d H:i:s',time());
//        dd($code);
        //存入session里面
        session(['user_code'=>$code],60);
        //发送模板消息
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
        $data =[
            "touser"=>"$openid",
            "template_id"=>'VmUdYa1mUNkQGJfgAKSiyL8MRy1SStEcZocQx4axuyQ',
            "data"=>[
                "code"=>[
                    "value"=>"$code",
                    "color"=>"#173177",
                ],
                "name"=>[
                    "value"=>"$adminData->user_name",
                    "color"=>"#173177",
                ],
                "time"=>[
                    "value"=>"$time",
                    "color"=>"#173177",
                ],
            ],
        ];
        $data = json_encode($data);
//        dd($data);
        $req = $this->curl->post($url,$data,JSON_UNESCAPED_UNICODE);
        $req=json_decode($req,1);
        if($req['errmsg']=='ok'){
            return json_encode(['code'=>1,'msg'=>'发送验证码成功']);
        }
    }
    /*
     * 微信绑定页面
     * */
    public function bangding()
    {
        return view('login1/bangding');
    }
    /*
     * 微信绑定页面处理
     * */
    public function bangding_do()
    {

        //接收
       $post = request()->except('_token');
//       dd($post);
        $post['create_time']=time();
       $user_data =  DB::table('user_info')->where(['user_name'=>$post['user_name']])->first();
      $user_data = json_decode(json_encode($user_data),1);
//       dd($user_data);
        if($user_data['user_name'] != $post['user_name']){
            echo ('用户名不正确');die;
        }
        if($user_data['user_pwd'] != $post['user_pwd']){
            echo ('请输入正确的密码');die;
        }
        $redirect_uri = 'http://www.1902.com/admin1/code';
//        dd($redirect_uri);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
//       dd($url);
        header('Location:'.$url);
    }
    /*
    * 获取code码 换取access_token
    */
    public function code(Request $request)
    {
        $post= request()->all();
//        dd($post);
      $url =  'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$post['code'].'&grant_type=authorization_code';
      $req = file_get_contents($url);
//      dd($req);
        $req = json_decode($req,1);
        //获取用户信息
        $url1 = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$req['access_token'].'&openid='.$req['openid'].'&lang=zh_CN';
        $res = file_get_contents($url1);
        $res = json_decode($res,1);
       $openid = $res['openid'];
       $date = DB::table('user_info')->update(['openid'=>$openid]);
//        if ($date){
//            <script> alert('绑定成功')</script>;
//        }
    }


}
