<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Tools\Tools;

class Ceshi2Controller extends Controller
{
    public $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    //测试 任务调度
    public function ceshi()
    {
//        dd(56156);
        //获取用户列表接口
        $user_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->tools->get_wechat_access_token().'&next_openid=';
//        dd($user_url);
        $req = file_get_contents($user_url);
        $req = json_decode($req,1);
//        dd($req);
        foreach($req['data']['openid'] as $v){
            //获取用户基本信息
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid='.$v.'&lang=zh_CN';
            $user_re = file_get_contents($url);
            $user_info = json_decode($user_re,1);
//            dd($user_info);

                $list_openid = DB::connection('1901')->table('wechat_openid')->where(['openid'=>$v])->first();
                if(empty($list_openid)){
                    DB::connection('1901')->table('wechat_openid')->where(['openid'=>$v])->insert([
                        'openid'=>$v,
                        'add_time'=>time(),
                    ]);
//                    dd($list_openid);
                    //未签到
                    //发送模板消息接口
                    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->tools->get_wechat_access_token();
//        dd($url);
                    $data = [
                        'touser'=>'oIhHHwSoKsFZacgx0NZ9B9RV6xmg',
                        'template_id'=>'wdZiz1cGT1DmRu9A8XuyEtNeCKv6B-EIHxJW4BnkAeY',
                        "url"=>"http://weixin.qq.com/download",
                        'data'=>[
                            'keyword1'=>[
                                'value'=>$user_info['nickname'],
                                'color'=>'',
                            ],
                            'keyword2'=>[
                                'value'=>'未签到',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'0',
                                'color'=>'',
                            ],
                            'keyword3'=>[
                                'value'=>'',
                                'color'=>'',
                            ],
                        ],
                    ];
                    dd($data);

                    $res = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
                   dd($res);


                }

        }

    }

    //添加自定义菜单
    public function create_menu()
    {
        dd(date('Y-m-d H:i:s' ,1569314229));
        return view('ceshi/create_menu');
    }
    //自定义菜单处理
    public function create_menu_do()
    {
        //接收
        $post = request()->except('_token');
//        dd($post);
        $post['button_type'] = empty($post['name2'])?1:2;
//        dd($post);
//        入库
        $req = DB::connection('1901')->table('wechat_menu')->insertGetId($post);
//        dd($req);
        if(!$req){
            dd('添加失败');
        }
        //根据数据库表数据翻译成菜单结构
        $this->menu();
    }
    /***
     * 数据库菜单表格数据
     */
    /** 数据库菜单表格数据*/
    public function menu()
    {
        $data = [];
//         $event_arr = [1=>'click',2=>'view'];
        $menu_list = DB::connection('1901')->table('wechat_menu')->select(['name1'])->groupBy('name1')->get(); // 获取一级菜单
//        dd($menu_list);
        foreach($menu_list as $vv) {
            $menu_info = DB::table('wechat_menu')->where(['name1'=>$vv->name1])->get(); // 获取一级菜单下所有 三维数组
//             dd($menu_info);
            $menu = [];
            foreach ($menu_info as $v) {
                $menu[] = (array)$v; // 获取一级菜单下所有中的一个 二维数组

            }
//             dd($menu);
            $arr = [];
            foreach ($menu as $v) { // 获取一级菜单下所有中的一个 一维数组
                if ($v['button_type'] == 1) { // 普通一级菜单
                    if ($v['type'] == 1) { // click
                        $arr = [
                            'type' => 'click',
                            'name' => $v['name1'],
                            'key'  => $v['event_value']
                        ];
                    }elseif ($v['type'] == 2) { // view
                        $arr = [
                            'type' => 'view',
                            'name' => $v['name1'],
                            'url'  => $v['event_value']
                        ];
                    }
                }elseif ($v['button_type'] == 2) { // 带有二级菜单的一级菜单
                    $arr['name'] = $v['name1'];
//                     dd($arr);
                    if ($v['type'] == 1) { // click
                        $button_arr = [
                            'type' => 'click',
                            'name' => $v['name2'],
                            'key'  => $v['event_value']
                        ];
                    }elseif ($v['type'] == 2) { // view
                        $button_arr = [
                            'type' => 'view',
                            'name' => $v['name2'],
                            'url'  => $v['event_value']
                        ];
                    }
                    $arr['sub_button'][] = $button_arr;
                }
            }
//             dd($v);
            $data['button'][] = $arr;
        }
//         dd($arr);

        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->tools->get_wechat_access_token();
//         dd($url);
        $res = $this->tools->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//         dd($res);
        $result = json_decode($res,1);
        dd($result);
    }

}
