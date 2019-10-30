<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    protected $table = 'item';//关联数据表
    protected $primaryKey = 'item_id';//定义主键
    public $timestamps = false;//关闭自动时间戳
    protected $guarded = [];//不可以注入数据字段
}
