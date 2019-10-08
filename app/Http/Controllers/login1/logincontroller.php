<?php

namespace App\Http\Controllers\login1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\model\Curl;
use App\Tools\Tools;
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

        dd($post);
        //查询数据库
        $adminData = DB::table('user_info')->where(['user_name'=>$post['user_name']])->first();
//        dd($adminData);
        if($adminData->user_name != $post['user_name']){
            dd('用户名不存在');
        }
       if($adminData->user_pwd != $post['user_pwd']){
           dd('密码不正确');
       }

        $openid = $adminData->openid;
//        dd($openid);
        //发送验证码 4位 或6位
        $code = rand(0000,9999);
//        dd($code);
        $time=date('Y-m-d H:i:s',time());
//        dd($code);
        //存入session里面
        session(['code'=>$code]);
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
//        dd($req);
    }
    /*
     * 发送验证码
     */
    public function code()
    {
     //接收
//        return 123;
        $code = rand(0000,9999);
//        dd($code);
        $time=date('Y-m-d H:i:s',time());
//        dd($code);
        //存入session里面
        session(['code'=>$code]);
        //发送模板消息
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
        $data =[
            "touser"=>"oIhHHwSoKsFZacgx0NZ9B9RV6xmg",
            "template_id"=>'VmUdYa1mUNkQGJfgAKSiyL8MRy1SStEcZocQx4axuyQ',
            "data"=>[
                "code"=>[
                    "value"=>"$code",
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
//        return $req;
        if($req['errmsg']=='ok'){
            echo json_encode(['msg'=>'发送cg','code'=>1]);
        }else{
            echo json_encode(['msg'=>'发送失败','code'=>0]);
        }
    }
    /*
     * 微信绑定页面
     * */
    public function bangding()
    {
        return view('login1/bangding');
    }
}
