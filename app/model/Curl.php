<?php

namespace App\model;


class Curl
{
    //curl get 请求
    public function get($url)
    {
        //1.初始化
        $ch = curl_init();
        //2.设置选项
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //如果是https访问设置
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        //3.执行
        $output = curl_exec($ch);
        //4.关闭
        curl_close($ch);

        return $output;
    }
    //curl post请求
    public function post($url,$data)
    {
        //1.初始化
        $ch = curl_init();
        //2.设置选项
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //post数据请求
        curl_setopt($ch,CURLOPT_POST,1);
        //post的数据
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        //如果是https访问设置
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        //3.执行
        $output = curl_exec($ch);
        //4.关闭
        curl_close($ch);

        return $output;
    }
    /**
     * 网页授权获取用户openid
     * @return [type] [description]
     */
    public static function getOpenid()
    {
        //echo 1;die;
        //先去session里取openid
        $openid = session('openid');
        //var_dump($openid);die;
        if(!empty($openid)){
            return $openid;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appid."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appid."&secret=".self::secret."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            //获取到openid之后  存储到session当中
            session(['openid'=>$openid]);
            return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
        }
    }
}
