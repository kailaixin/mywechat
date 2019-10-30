<?php

namespace App\Http\Controllers\admin1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class admincontroller extends Controller
{
    //后台首页
    public function index()
    {
        return view('admin1/index');
    }
    //后台主页
    public function index_v1()
    {
        return view('admin1/index_v1');
    }
}
