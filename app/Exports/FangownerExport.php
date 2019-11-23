<?php

// excel导出数据类
namespace App\Exports;

use App\Models\FangOwner;
use Maatwebsite\Excel\Concerns\FromCollection;

class FangownerExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 获取所有的房东数据
        return FangOwner::get(['id','name']);
    }
}
