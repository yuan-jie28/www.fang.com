<?php

namespace App\Http\Controllers\Admin;

use App\Models\FangOwner;
use App\Models\Notice;
use App\Models\Renting;
use Illuminate\Http\Request;

class NoticeController extends BaseController
{
    // 列表
    public function index()
    {
        // 分页获取通知数据
        $data = Notice::with(['fangowner','renting'])->paginate($this->pagesize);
        return view('admin.notice.index',compact('data'));
    }

    // 添加显示
    public function create(Request $request)
    {
        // 获取数据
        if ($request->ajax()) {
            // 房东数据
            $fdata = FangOwner::all();
            // 租客数据
            $rdata = Renting::all();
            return [$fdata, $rdata];
        }

        return view('admin.notice.create');
    }

    // 添加处理
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'cnt' => 'required'
            ]);
            // 入库操作
            Notice::create($request->except('_tken'));
            return ['status' => 0, 'mag' => '成功', 'url' => route('admin.notice.index')];
        } catch (\Exception $exception) {
            // 验证有异常
            return ['status' => 0, 'msg' => '验证异常', 'data' => $exception->validate->messages()];

        }
    }

    // 展示
    public function show(Notice $notice)
    {
        //
    }

    // 修改显示
    public function edit(Notice $notice)
    {
        //
    }

    // 修改处理
    public function update(Request $request, Notice $notice)
    {
        //
    }

    // 删除
    public function destroy(Notice $notice)
    {
        //
    }
}
