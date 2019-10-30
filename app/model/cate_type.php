<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class cate_type extends Model
{
    //关联数据表
    protected $table = 'cate_type';
    //定义主键id
    protected $primaryKey='type_id';
    //关闭自动时间戳
    public $timestamps = false;
}
