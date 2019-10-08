<?php

namespace App\model;


class Curl
{
    //curl get 请求
    public function get($curl)
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
}
