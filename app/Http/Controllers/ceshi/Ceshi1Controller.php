<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
use DB;

class Ceshi1Controller extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools=$tools;
    }


    //微信授权登录页面
    public function wechat_login()
    {
        return view('ceshi/wechat_login');
    }

    //微信授权页面登录处理
    public function wechat_login_do()
    {
       //第一步 用户同意授权 ,获取code
        $redirect_uri = 'http://www.1902.com/ceshi1/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header('Location:'.$url);
    }

    //通过code换取网页授权access_token
    public function code(Request $request)
    {
        $req = request()->all();
//        dd($req);
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$req['code'].'&grant_type=authorization_code';
        $res = file_get_contents($url);
        $res = json_decode($res,1);

        $url1 = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$res['access_token'].'&openid='.$res['openid'].'&lang=zh_CN';
        $req = file_get_contents($url1);
        $req = json_decode($req,1);
//        dd($req);
        if($req){
            return redirect('');
        }

    }
    //获取用户列表
    public function get_userinfo()
    {
//                https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_wechat_access_token().'&next_openid=';
        $req = file_get_contents($url);
        $req = json_decode($req,1);
//        $this->tools->redis->set('user_info',$req);
//        $user_info = $this->tools->redis->get('user_info');
        $user_info = [];
        foreach ($req['data']['openid'] as $k => $v){
            $user_info1 =  file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN');
            $user_info1 = json_decode($user_info1,1);
//            dd($user_info1);
            $user_info[$k]['nickname'] = $user_info1['nickname'];
            $user_info[$k]['sex'] = $user_info1['sex'];
            $user_info[$k]['headimgurl'] = $user_info1['headimgurl'];
            $user_info[$k]['subscribe_time'] = $user_info1['subscribe_time'];
            $user_info[$k]['openid'] = $user_info1['openid'];
        }
//        dd($user_info);
        foreach ($user_info as $v){
            $res = DB::table('myfans')->insert($v);
        }


    }
    //粉丝列表展示
    public function userinfo()
    {
        $tag_id = request()->tag_id;
//        dd($tag_id);
        $data = DB::table('myfans')->get();
        $data = json_encode($data,1);
        $data = json_decode($data,1);
//       dd($data);
        return view('ceshi/userinfo',['data'=>$data,'tag_id'=>$tag_id]);
    }

    //创建用户标签
    public function biaoqian()
    {
        return view('ceshi/biaoqian');
    }
    //用户标签处理
    public function biaoqian_do()
    {
       $post = request()->except('_token');
//       dd($post);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->tools->get_wechat_access_token().'';
//        dd($url);
        $data = [
            'tag'=>[
                'name'=>$post['name'],
            ]
        ];

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
//        dd($data);
        $req = $this->tools->curl_post($url,$data);
        if($req){
         return redirect('ceshi1/tag_list');
        }

    }


    //标签列表展示
    public function tag_list()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->tools->get_wechat_access_token().'';
        $tag_info = file_get_contents($url);
        $tag_info = json_decode($tag_info,JSON_UNESCAPED_UNICODE);
//        dd($tag_info);
        return view('ceshi/tag_list',['tag_info'=>$tag_info['tags']]);
    }
    //删除用户标签
    public function tag_del()
    {
        $id = request()->tag_id;
//        dd($id);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$this->tools->get_wechat_access_token().'';
//        dd($url);
        $data = [
            'tag'=>[
                'id'=>$id,
            ]
        ];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
//
        $req = $this->tools->curl_post($url,$data);
        $req=json_decode($req,1);
//        dd($req);
        if($req['errmsg']=='ok'){
            return redirect('ceshi1/tag_list');
        }
//
    }
    //为粉丝打标签
    public function tag_save()
    {
        $post = request()->all();
//        dd($tag_id);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->tools->get_wechat_access_token().'';
//        dd($url);
        $data = [
            "openid_list"=>[//粉丝openid
                   $post['openid'],
            ],
            'tagid'=>$post['tag_id'],
        ];
//        dd(json_encode($data,1));
        $req = $this->tools->curl_post($url,json_encode($data,1));
        $req=json_decode($req,1);
//        dd($req);
        if($req['errmsg']=='ok'){
            return redirect('ceshi1/tag_list');
        }
    }
    //用户标签群发消息
    public function tag_word()
    {
        $tag_id = request()->tag_id;
//        dump($tag_id);
        return view('ceshi/tag_word',['tag_id'=>$tag_id]);
    }

    //用户标签群发处理
    public function tag_word_do()
    {
        $post = request()->all();
        dd($post);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->tools->get_wechat_access_token().'';
//        dd($url);
        $data = [
            "filter"=>[
                "is_to_all"=>false,
                'tag_id'=>$post['tagid'],
            ],
            "text"=>[
                'content'=>$post['word']
            ],
            'msgtype'=>'text',
        ];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
//        dd($data);
        $req = $this->tools->curl_post($url,$data);
        $req = json_decode($req,1);
//        dd($req);
        if($req['errcode']=='0'){
            return redirect('ceshi1/tag_list');
        }
    }

}
