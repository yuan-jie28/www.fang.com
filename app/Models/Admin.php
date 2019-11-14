<?php

namespace App\Models;

// 此类提供auth验证方法
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // 黑名单
    protected $guarded = [];

    // 修改器
    // set字段名(首字母大写)Attribute
    // 如果字段有下划线，则下划线不要，而下划线后面的首字母要大写
    public function setPasswordAttribute($value)
    {
        // 给密码字段加密
        $this->attributes['password'] = bcrypt($value);
    }
}
