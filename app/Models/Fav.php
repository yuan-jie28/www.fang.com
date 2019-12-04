<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fav extends Base
{
    // 房源属性
    public function fang()
    {
        return $this->belongsTo(Fang::class,'fang_id');
    }
}
