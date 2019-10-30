<?php

namespace App\Http\Controllers\ceshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\stu_table;
use App\model\stu_class;
use DB;
use App\Tools\Aes;
use App\Tools\Rsa;

class WeekendController extends Controller
{
//    /**
//     * 构造方法
//     */
//    public $aes;
//    public function __construct(Aes $aes)
//    {
//        $this->aes = $aes;
//    }

    /**
     * 周末 数组数据处理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
        public function index ()
        {
            $data = stu_class::get()->toArray();
//            dump($data);
            $classInfo=[];//班级信息
            foreach ($data as $key=>$value){
                    $class =stu_table::where(['class_id'=>$value['class_id']])->get()->toArray();
                    $classInfo[$key]['count'] =count($class);
                    $classInfo[$key]['class_id'] =$value['class_id'];
                    $classInfo[$key]['class_name'] =$value['class_name'];
            }
//            dump($classInfo);
            return view('test/index',[
                'classInfo'=>$classInfo,
            ]);
        }

    /**
     *对称加密
     *采用AES  128位 ECB模式
     */
        public function aes()
        {
            $obj = new Aes('1234567890123456');
            $data = "你好 世界";
            echo $eStr = $obj->encrypt($data);  //加密后的密文
            echo "<hr>";
            echo $obj->decrypt($eStr);

        }

    /**
     * 非对称加密
     */
        public function rsa()
        {
            //举个粒子
            $Rsa = new Rsa();
//             $keys = $Rsa->new_rsa_key(); //生成完key之后应该记录下key值，这里省略
//            echo "<pre>";
//             print_r($keys);die;
            $privkey = file_get_contents("rsa/cert_private.pem");//$keys['privkey'];私钥
            $pubkey  = file_get_contents("rsa/cert_public.pem");//$keys['pubkey'];公钥
            //echo $privkey;die;
            //初始化rsaobject
            $Rsa->init($privkey, $pubkey,TRUE);

            //原文
            $data = '你好 php';

            //私钥加密示例
            $encode = $Rsa->priv_encode($data);
            echo '<pre>';
            print_r($encode);
            $ret = $Rsa->pub_decode($encode);
            echo '<pre>';
            print_r($ret);

            //公钥加密示例
            $encode = $Rsa->pub_encode($data);
            echo '<pre>';
            print_r($encode);
            $ret = $Rsa->priv_decode($encode);
            echo '<pre>';
            print_r($ret);



            function p($str){
            echo '<pre>';
            print_r($str);
            echo '</pre>';
            }
        }
}
