<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class attribute extends Model
{
    //关联数据表
    protected $table = 'attribute';
    //定义主键id
    protected $primaryKey='attr_id';
    //关闭自动时间戳
    public $timestamps = false;


}
