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

    //添加自定义菜单
    public function create_menu()
    {
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

    //关注,取消关注事件
    public function event()
    {
//        echo $_GET['echostr'];die();
//        dd($_POST);
        $xml_string = file_get_contents('php://input'); // 获取微信发过来的xml数据
//        dd($xml_string);
        $wechat_log_path = storage_path('/logs/wechat/'.date("Y-m-d").'.log');  // 生成日志文件
        file_put_contents($wechat_log_path,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_path,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_path,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);

//        dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        $xml_arr = (array)$xml_obj;
//        dd($xml_arr);
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
//        echo $_GET['echostr'];


        // 业务逻辑（防止刷业务）
        if ($xml_arr['MsgType'] == 'event') {
            if ($xml_arr['Event'] == 'subscribe') {
                $share_code = explode('_',$xml_arr['EventKey'])[1];
                $user_openid = $xml_arr['FromUserName']; // 粉丝的openid
                // 判断是否已经在日志里
                $wechat_openid = DB::table('wechat_openid')->where('openid',$user_openid)->first();
                if (empty($wechat_openid)) {
                    DB::table('users')->where('id',$share_code)->increment('share_num',1);
                    DB::table('wechat_openid')->insert([
                        'openid' => $user_openid,
                        'add_time' => time()
                    ]);
                }
            }
        }

        $message = '欢迎关注,不要走开,精彩继续';
        $xml_str = '<xml><ToUserName><![CDATA['.$xml_arr['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml_arr['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
        echo $xml_str;
    }
}
