<?php

namespace App\Http\Resources;

use App\Models\Fangattr;
use Illuminate\Http\Resources\Json\JsonResource;

class FangResource extends JsonResource
{
    // 单个模型对象输出的指定
    public function toArray($request)
    {
        return [
            // 为了安全，前面输出的字段可以随便起  也就是说输出的字段与数据表中的字段不一致
            'id' => $this->id,
            'name' => $this->fang_name,
//            'shi'=> Fangattr::where('id',$this->fang_shi)->value('name'),
            'room' => Fangattr::where('id',$this->fang_shi)->value('name')  . Fangattr::where('id',$this->fang_ting)->value('name'),
            'pic' => $this->fang_pic[0],
            'rent' => $this->fang_rent,
            'area' => $this->fang_build_area
        ];
    }
}
