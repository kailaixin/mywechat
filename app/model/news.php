<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class news extends Model
{
    protected $table = 'news';//关联数据表
    protected $primaryKey = 'new_id';//定义主键
    protected $connection = "1902";
    public $timestamps = false;//关闭自动时间戳
    protected $guarded = [];//不可以注入数据字段
}
