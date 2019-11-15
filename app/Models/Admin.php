<?php

namespace App\Models;

// 此类提供auth验证方法
use Illuminate\Foundation\Auth\User as Authenticatable;
// 软删除  导入类文件
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    // 继承  trait
    use SoftDeletes;
    // 指定软删除字段  deleted_at 数据表中的字段
    protected $dates = ['deleted_at'];


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

    // 用户与角色之间的关系为  属于
    public function role()
    {
        // 参1  关联模型
        // 参2  本模型对应关联模型的对应字段ID
        return $this->belongsTo(Role::class,'role_id');
    }
}
