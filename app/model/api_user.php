<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class api_user extends Model
{
    //关联数据表
    protected $table = 'api_user';
    //定义主键id
    protected $primaryKey='api_id';
    //关闭自动时间戳
    public $timestamps = false;
}
