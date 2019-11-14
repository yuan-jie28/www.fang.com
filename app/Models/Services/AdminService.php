<?php

namespace App\Models\Services;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminService
{
    // 用户列表搜索业务
    public function getList(Request $request, int $pagesize=10)
    {
        // 时间
        // 得到的是年月日
        $st = $request->get('st');
        $et = $request->get('et');
        // 账号
        $kw = $request->get('kw');
        // 条件
        // when方法如果参数1条件成功，则执行回调方法
        return Admin::when($st, function($query) use ($st,$et){
            $st = date('Y-m-d 00:00:00',strtotime($st));
            $et = date('Y-m-d 23:59:59',strtotime($et));
            $query->whereBetween('created_at',[$st,$et]);
        })->when($kw,function($query) use ($kw){
            $query->where('username','like',"%{$kw}%");
        })->orderBy('id','desc')->paginate($pagesize);
    }
}
