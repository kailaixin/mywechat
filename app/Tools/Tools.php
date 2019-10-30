<?php

namespace App\Tools;

class Tools {
    public $redis;

    public function __construct()
    {
//        echo phpinfo();die;
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
    }

    /**
     * 获取access_token
     * @return bool|string
     */
    public function get_wechat_access_token()
    {
        //加入缓存
        $access_token_key = 'wechat_access_token';
        if($this->redis->exists($access_token_key)){
            //存在
            return $this->redis->get($access_token_key);
        }else{
            //不存在
            $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'');
//            dd($result);
            $re = json_decode($result,1);
//            dd($re);
            $this->redis->set($access_token_key,$re['access_token'],$re['expires_in']);  //加入缓存
            return $re['access_token'];
        }
    }
    /**
     * 获取get_wechat_ticket
     * @return bool|string
     */
    public function get_wechat_ticket()
    {
        //加入缓存
        $access_api_key = 'wechat_ticket';
//        dd($access_api_key);
        if($this->redis->exists($access_api_key)){
            //存在
            return $this->redis->get($access_api_key);
        }else{
            //不存在
            $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->get_wechat_access_token().'&type=jsapi');
            $re = json_decode($result,1);
//            dd($re);
            $this->redis->set($access_api_key,$re['ticket'],$re['expires_in']);  //加入缓存
            return $re['ticket'];
        }
    }

    /**
     * curl_get 方式发送
     * @param $url
     * @return bool|string
     */
    public static function get($url)
    {
        //初始化
        $ch = curl_init();
        //设置选项
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        //如果是https访问
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        //执行
        $output = curl_exec($ch);
        //关闭
        curl_close($ch);
        return $output;
    }
    /**
     * curl_post 方式发送
     * @param $url
     * @param $data
     * @return bool|string
     */
    public function curl_post($url,$data)
    {
//        dd(13213);
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,true);  //发送post
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $data = curl_exec($curl);
//        dd($data);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        curl_close($curl);
        return $data;
    }

    /**
     * guzzle 方式上传素材
     * @param $url
     * @param $path
     * @param $client
     * @return mixed
     */
    public function guzzle_upload($url,$path,$client,$is_video=0,$title='',$desc='')
    {
        $multipart = [
            [
                'name' => 'media',
                'contents' => fopen($path,'r')
            ]
        ];
        if($is_video == 1){
            $multipart[] = [
                'name'=>'description',
                'contents' => json_encode(['title'=>$title,'introduction'=>$desc],JSON_UNESCAPED_UNICODE)
            ];
        }
        $result = $client->request('POST',$url,[
            'multipart' => $multipart
        ]);
        return $result->getBody();
    }
    /*
     * 无限分类方法
     * */
   public function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['parent_id'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['cate_id'], $level+1);

            }
        }
        return $list;
    }
}
