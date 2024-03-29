<?php

namespace App\Models;

use App\Observers\FangObserver;

class Fang extends Base
{
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::observe(FangObserver::class);
    }

    // 定义房源与房东之间的模型关系以及通过属性字段ID来获取对应的属性名称数据
    // 房东  关联模型
    public function fangowner()
    {
        return $this->belongsTo(FangOwner::class,'fang_owner');
    }

    // 属性
    public function getAttrIdByName($id)
    {
        if (!is_array($id)){
            // 一对一
            return Fangattr::where('id',$id)->value('name');
        }
        $names = Fangattr::whereIn('id',$id)->pluck('name')->toArray();
        return implode(',',$names);
    }

    // 获取器
    public function getPicAttribute()
    {
        $arr = explode('#',$this->attributes['fang_pic']);
        array_shift($arr);
        return $arr;
    }

    // 获取器  把图片地址转为绝对地址
    public function getFangPicAttribute()
    {
        $arr = explode('#',$this->attributes['fang_pic']);
        // 去除数据中的第一个元素
        array_shift($arr);
        return array_map(function ($item){
            return self::$host . '/' . ltrim($item,'/');
        },$arr);
    }

    // 获取器
    public function getFangPicsAttribute()
    {
        return $this->attributes['fang_pic'];
    }
}
