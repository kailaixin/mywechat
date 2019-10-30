<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class TestController extends Controller
{
    //添加
    public function add(Request $request)
    {
//        dd($time);
        $name = $request->input('name');
        $sex = $request->input('sex');
        $age = $request->input('age');
        $sign = MD5('1902a'.$name.$age);
        if(empty($name) || empty($sex) || empty($age)){
            return json_encode(['msg'=>'请填写参数','code'=>0]);die;
        }
        //判断文件是否存在
        if ($request->hasFile('stu_image')) {
            $file = $request->file('stu_image');
//            dd($file);
        }
//      验证上传是否成功
        if ($request->file('stu_image')->isValid()) {
            $path = $request->stu_image->store('images');
//            dd($path);
        }
        //添加入库
        $res = DB::table('student')->insert([
                    'stu_name'=>$name,
                    'stu_sex'=>$sex,
                    'stu_age'=>$age,
                    'stu_image'=>$path,
        ]);
        if ($res){
            return json_encode(['msg'=>'添加成功','code'=>1]);die;
        }else{
            return json_encode(['msg'=>'添加失败','code'=>0]);die;
        }

    }
    //展示
    public function show()
    {
        //搜索 带一个搜索参数
        $where=[];
        $name =request('name');
        $sex =request('sex');
//        dump($name);
        if(isset($sex)){
            $where[]=[
                'stu_sex','like',"%$sex%",
            ];
        }
        if(isset($name)){
            $where[]=[
                'stu_name','like',"%$name%",
            ];
        }
        $data = DB::table('student')->where($where)->paginate(3);
//        dd($data);

        return json_encode(['data'=>$data,'code'=>200,'msg'=>'查询成功']);
    }

    //修改
    public function update(Request $request)
    {
        $id = $request->id;
//        dump($id);
        $find = DB::table('student')->where(['stu_id'=>$id])->first();
//        dd($find);
        return json_encode(['find'=>$find,'msg'=>'查询成功','code'=>'200']);
    }
    //执行修改
    public function update_do(Request $request)
    {
        //接收值
        $post = $request->all();
//        dd($post);
        //修改
        $res = DB::table('student')->where(['stu_id'=>$post['id']])->update([
            'stu_name'=>$post['name'],
            'stu_sex'=>$post['sex'],
            'stu_age'=>$post['age'],
        ]);
//        dd($res);
        return json_encode(['res'=>$res,'code'=>200,'msg'=>'修改成功']);
    }

    //删除
    public function delete(Request $request)
    {
        //接收值
        $id = $request->id;
//        dd($id);
        //删除
        $res = DB::table('student')->where(['stu_id'=>$id])->delete();
//        dd($res);
        return json_encode(['res'=>$res,'msg'=>'删除成功','code'=>'200']);
    }

    public function jiekou ()
    {
        $url = 'http://wym.yingge.fun/api/user/test';

        $name = "开来鑫";
        $age = "18";
        $mobile = "15500805364";
        $rand = rand(0000,9999);
        $sign = sha1("1902".$name.$age.$mobile.$rand);
        $data=[
            'name'=>$name,
            'age'=>$age,
            'mobile'=>$mobile,
            'rand'=>$rand,
            'sign'=>$sign,
        ];
        $data = json_encode($data);
        $res = $this->curl_post($url,$data);
        dd($res);
    }
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
}
