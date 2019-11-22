<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FangAttrRequest;
use App\Models\FangAttr;
use Illuminate\Http\Request;

class FangAttrController extends BaseController
{
    // 列表
    public function index(Request $request)
    {
        // ajax请求
        if ($request->ajax()) {
            $data = FangAttr::all()->toArray();
            // 层级的展示
            $data = treeLevel($data);
            return $data;
        }
        return view('admin.fangattr.index');
    }

    // 添加显示
    public function create()
    {
        // 读取顶级房源属性
        $data = FangAttr::where('pid', 0)->pluck('name', 'id')->toArray();
        $data[0] = '==顶级==';
        return view('admin.fangattr.create', ['data' => $data]);
    }

    // 添加处理  表单接受
    public function store(FangAttrRequest $request)
    {
        $data = $request->except(['file', '_token']);
        FangAttr::create($data);
        return redirect(route('admin.fangattr.index'));
    }

    // 修改显示
    public function edit(FangAttr $fangattr)
    {
        $fangAttr = $fangattr;
        // 读取顶级房源属性
        $data = FangAttr::where('pid', 0)->pluck('name', 'id')->toArray();
        $data[0] = '==顶级==';
        return view('admin.fangattr.edit', compact('fangAttr', 'data'));
    }

    // 修改处理
    public function update(FangAttrRequest $request, FangAttr $fangattr)
    {
        $data = $request->except(['_token', 'file', '_method']);
        $fangattr->update($data);
        return redirect(route('admin.fangattr.index'))->with('success', '修改成功');
    }

    // 删除操作
    public function destroy(FangAttr $fangattr)
    {
        $fangattr->delete();
        return ['status' => 0, 'msg' => '删除成功'];
    }
}
