<?php
// 用户管理
namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    // 列表
    public function index(Request $request)
    {
        // 用户列表数据
        //$data = Admin::orderBy('id','desc')->paginate($this->pagesize);
        // 读取业务层中获取到的搜索用户列表数据
        $data = (new AdminService())->getList($request, $this->pagesize);
        return view('admin.admin.index', compact('data'));
    }

    // 添加显示
    public function create()
    {
        return view('admin.admin.create');
    }

    // 添加处理
    public function store(Request $request)
    {
        // 表单验证
        $this->validate($request, [
            // unique唯一不重复
            // 添加时语法  unique:table,column
            'username' => 'required|unique:admins,username',
            'truename' => 'required',
            'email' => 'nullable|email',
            // confirmed 验证两个表单项是否数据一致
            // 如果验证字段名为password，则需要和此字段中的数据一致的字段，名称一定要叫password_confirmation
            'password' => 'required|confirmed',
        ]);
        // 获取数据
        $data = $request->except(['_token', 'password_confirmation']);
        // 密码加密 可以用修改器来给指定的字段加密
        // $data['password'] = bcrypt($data['password']);
        // 验证通过添加数据表中
        $model = Admin::create($data);

        return redirect(route('admin.user.index'))->with('success', '添加用户【' . $model->truename . '】成功');
    }

    // 修改用户显示  获取路由参数  依赖注入
    public function edit(int $id)
    {
        // 获取当前用户的信息
        $data = Admin::find($id);
        return view('admin.admin.edit', compact('data'));
    }

    // 修改用户处理
    public function update(Request $request, int $id)
    {
        // 表单验证
        $data = $this->validate($request, [
            // unique唯一不重复
            'username' => 'required|unique:admins,username,' . $id,
            'truename' => 'required',
            'email' => 'nullable|email',
            'password' => 'nullable|confirmed',
            'sex' => 'in:先生,女士'
        ]);

        if (!$data['password']) {
            unset($data['password']);
        }

        // 修改
        Admin::where('id', $id)->update($data);
        return redirect(route('admin.user.index'))->with('success', '修改用户【' . $data['truename'] . '】成功');
    }
}
