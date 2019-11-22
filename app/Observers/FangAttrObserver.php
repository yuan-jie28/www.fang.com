<?php
// 房源属性观察者
namespace App\Observers;

use App\Models\FangAttr;

class FangAttrObserver
{
    // 添加数据之前------  只有在模型中调用create方法添加数据才会触发它
    public function creating(FangAttr $fangAttr) {
        // request对象中的get不是使用empty或用isset
        $field_name = request()->get('field_name');
        $fangAttr->field_name = $field_name == null ? '' : $field_name;
    }
   //修改数据之前触发
    public function updating(FangAttr $fangAttr) {
        // request对象中的get不是使用empty或用isset
        $field_name = request()->get('field_name');
        $fangAttr->field_name = $field_name == null ? '' : $field_name;
    }
}
