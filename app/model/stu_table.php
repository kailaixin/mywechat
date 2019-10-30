<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class stu_table extends Model
{
    //关联数据表
    protected $table = 'stu_table';
    //定义主键id
    protected $connection = "1902";
    protected $primaryKey='stu_id';
    //关闭自动时间戳
    public $timestamps = false;
}
