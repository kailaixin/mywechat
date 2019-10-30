<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';//关联数据表
    protected $primaryKey = 'goods_id';//定义主键
    public $timestamps = false;//
    protected $guarded = [];//不可以注入数据字段
}
