<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApiuserRequest;
use App\Models\Apiuser;
use Illuminate\Http\Request;

class ApiuserController extends BaseController
{
    // 列表
    public function index()
    {
        $data = Apiuser::paginate($this->pagesize);
        return view('admin.apiuser.index',compact('data'));
    }

    // 添加显示
    public function create()
    {
        return view('admin.apiuser.create');
    }

    // 添加处理
    public function store(ApiuserRequest $request)
    {
        // 如果验证不通过，则抛出异常
        Apiuser::create($request->except('_token'));
        return ['status' => 0, 'msg' => '添加接口成功', 'url' => route('admin.apiuser.index')];
    }

    // 查看
    public function show(Apiuser $apiuser)
    {
        //
    }

    // 修改显示
    public function edit(Apiuser $apiuser)
    {
        //
    }

    // 修改处理
    public function update(Request $request, Apiuser $apiuser)
    {
        //
    }

    // 删除
    public function destroy(Apiuser $apiuser)
    {
        //
    }
}
