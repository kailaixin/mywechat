<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\Tools;
use DB;

class Ceshi3Controller extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    //课程管理登录页面
    public function guanli()
    {
//        dd(123);
      return view('ceshi3/guanli');
    }
    //第三方登录页面处理
    public function guanli_do()
    {
        $post = request()->all();
//        dd($post);
        $redirect_uri = 'http://www.1902.com/ceshi3/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
//        file_get_contents($url);
        header('Location:'.$url);
    }
    //code
    public function code()
    {
        $req = request()->all();
//        dd($req);
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$req['code'].'&grant_type=authorization_code';
        $res = file_get_contents($url);
//        dd($res);
        $res = json_decode($res,1);
//        dd($res);
        $url1 = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$res['access_token'].'&openid='.$res['openid'].'&lang=zh_CN';
        $re = file_get_contents($url1);
        $re = json_decode($re,1);

        $req = DB::connection('1901')->table('kecheng')->update(['openid'=>$re['openid']]);

            return redirect('ceshi3/manage');


    }
    //课程管理页面
    public function manage()
    {
        return view('ceshi3/manage');
    }
  //课程管理页面处理
    public function manage_do()
    {
        //接收
        $post = request()->except('_token');
        $post['add_time']=date('Y-m-d',time());
//        dd($post);
        //入库
        $res = DB::connection('1901')->table('kecheng')->insert($post);
//        dd($res);
        if($res){
            return redirect('ceshi3/index');
        }


    }
    //展示页面
    public function index()
    {
        //查询数据库
        $data = DB::connection('1901')->table('kecheng')->get();
//        dd($data);
      $day ="2019-9-1";
      $day = strtotime($day);
      $arr=[];
      foreach ($data as $v){
          if(strtotime($v->add_time) > $day){
                $arr[]=$v;
          }
      }
//        dd($arr);
        return view('ceshi3/index',['data'=>$arr]);
    }
    //修改页面
    public function update()
    {
        $id = request()->ke_id;
//        dd($id);up
        $data = DB::connection('1901')->table('kecheng')->where(['ke_id'=>$id])->first();
//        dd($data);
        return view('ceshi3/update',['data'=>$data]);
    }
    //修改处理
    public function update_do()
    {
        //接收
        $post = request()->except('_token');
//        dd($post);
        $count = DB::connection('1901')->table('kecheng')->where(['ke_id'=>$post['ke_id']])->first();
//        dd($count);
        if($count->update >3 && $count->openid){
            echo "<script> alert('您的修改次数已经达到了上限');location.href='/ceshi3/update'; </script>";die;
        }else{
            $data = DB::connection('1901')->table('kecheng')->update($post);
            if($data){
                return redirect('ceshi3/index');
            }
        }
    }
}
