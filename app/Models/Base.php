<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 引入软删除
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends Model
{
    use SoftDeletes;
    // 指定软删除字段 deleted_at 数据表中的字段
    protected $dates = ['deleted_at'];

    // create添加时所用
    protected $guarded = [];
}
