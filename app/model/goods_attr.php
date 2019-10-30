<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class goods_attr extends Model
{
    protected $table = 'goods_attr';//关联数据表
    protected $primaryKey = 'goods_attr_id';//定义主键
    public $timestamps = false;//关闭自动时间戳
    protected $guarded = [];//不可以注入数据字段
}
