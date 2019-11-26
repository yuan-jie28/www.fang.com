<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Base
{
    // 管联模型
    // 业主
    public function fangowner()
    {
        return $this->belongsTo(FangOwner::class,'fangowner_id');
    }

    // 租客
    public function renting()
    {
        return $this->belongsTo(Renting::class,'renting_id');
    }
}
