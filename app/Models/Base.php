<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 引入软删除
use Illuminate\Database\Eloquent\SoftDeletes;

// 引入按钮组train
use App\Models\Traits\Btn;

class Base extends Model
{
    // 多继承  trait 和 按钮组
    use SoftDeletes,Btn;
    // 指定软删除字段 deleted_at 数据表中的字段
    protected $dates = ['deleted_at'];

    // create添加时所用
    protected $guarded = [];
}
