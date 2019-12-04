<?php

namespace App\Models;

use App\Observers\FangAttrObserver;

class Fangattr extends Base {
    # 动态添加对象属性
    // 添加删除和修改按钮
    protected $appends = ['actionBtn'];

    // 观察者 boot 模型对象启时第1个执行的方法
    protected static function boot() {
        parent::boot();
        // 注册观察者
        self::observe(FangAttrObserver::class);
    }

    // 和访问器合作
    // 修改按钮和删除按钮
    public function getActionBtnAttribute() {
        // 多继承来实现权限按钮显示问题
        return $this->editBtn('admin.fangattr.edit') .' '. $this->delBtn('admin.fangattr.destroy');
    }

    // 获取修改icon字段的输出
    public function getIconAttribute()
    {
        if(stristr($this->attributes['icon'],'http')){
            return $this->attributes['icon'];
        }
        return self::$host . '/' . ltrim($this->attributes['icon'],'/');
    }

    // 这是在修改器中修改的   还有在模型观察者
    // 在添加数据和修改数据之前--------   只有在模型中调用create方法和update方法才会触发它
//    public function setFieldNameAttribute()
//    {
//        $field_name = request()->get('field_name');
//        $this->field_name = $field_name == null ? '' : $field_name;
//    }


}
