<?php

namespace App\Models;


class Role extends Base
{
    // 多对多  模型关系  查询  修改 添加  删除
    public function nodes()
    {
        // 参1  关联模型
        // 参2  中间表表名，不加前缀
        // 参3  本模型对应中间表的外键名
        // 参4  关联模型对应中间表的外键名
        return $this->belongsToMany(Node::class,'role_node','role_id','node_id');
    }
}
