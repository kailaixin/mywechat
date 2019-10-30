<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    //关联数据表
    protected $table = 'category';
    //定义主键id
    protected $primaryKey='cate_id';
    //关闭自动时间戳
    public $timestamps = false;

}
