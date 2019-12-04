<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FangGroupResource extends JsonResource
{
    // 单个模型对象输出的指定
    public function toArray($request)
    {
        return [
            // 为了安全，前面输出的字段可以随便起  也就是说输出的字段与数据表中的字段不一致
            'id' => $this->id,
            'gname' => $this->name,
            'pic' => $this->icon
        ];
    }
}
