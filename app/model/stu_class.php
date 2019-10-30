<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class stu_class extends Model
{
    //关联数据表
    protected $table = 'stu_class';
    protected $connection = "1902";

    //定义主键id
    protected $primaryKey='class_id';
    //关闭自动时间戳
    public $timestamps = false;

}
