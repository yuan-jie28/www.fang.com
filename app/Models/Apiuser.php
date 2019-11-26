<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
// 软删除  导入类文件
use Illuminate\Database\Eloquent\SoftDeletes;

class Apiuser extends Authenticatable
{
    use SoftDeletes;
    // 指定软删除字段 deleted_at 数据表中的字段
    protected $dates = ['deleted_at'];
    // 密码
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
        // 给字段添加一个明文的密码
        $this->attributes['plainpass'] = $value;
    }
}
