<?php
// 房东管理
namespace App\Http\Controllers\Admin;

use App\Http\Requests\FangOwnerRequest;
use App\Models\FangOwner;
use Illuminate\Http\Request;

// 导出的excel类
use Maatwebsite\Excel\Facades\Excel;
// 导出数据类
use App\Exports\FangownerExport;

class FangOwnerController extends BaseController
{
    // 列表
    public function index()
    {
        // 排序并分页
        $data = FangOwner::orderBy('id', 'desc')->paginate($this->pagesize);
        // 以下两种方法都可以实现数据展示
        // return view('admin.fangowner.index',compact('data'));
        return view('admin.fangowner.index')->with('data', $data);
    }

    // 添加显示
    public function create()
    {
        return view('admin.fangowner.create');
    }

    // 添加处理
    public function store(FangOwnerRequest $request)
    {
        $data = $request->except(['file', '_token']);
        FangOwner::create($data);
        return redirect(route('admin.fangowner.index'));
    }

    // 查看
    // 注意和路由参数中的名称要一致，包括大小写
    // 查看路由的命令  php artisan route:list|findstr owner
    public function show(FangOwner $fangowner)
    {
        // 得到图片展示
        $pics = $fangowner->pic;
        $picList = explode('#', $pics);
//        dump($picList);exit;
        if (count($picList) <= 1) {
            return ['status' => 1, 'mag' => '没有图片', 'data' => []];
        }
        // 去除第一个元素  也就是#号
        array_shift($picList);
        return ['status' => 0, 'msg' => '成功', 'data' => $picList];
    }

    // 导出房东excel
    public function export()
    {
        // 导出并下载
        // return Excel::download(new FangownerExport(),'fangowner.xlsx');

        // 导出并保存到服务器指定磁盘中
        // 参3 在config/filesystems.php文件中配置的上传文件的节点名称
        $obj = Excel::store(new FangownerExport(), 'fangowner.xlsx', 'fangownerexcel');

        // 返回true/false
        dump($obj);
    }

    // 修改显示
    public function edit(FangOwner $fangowner)
    {
        return view('admin.fangowner.edit', compact( 'fangowner'));
    }

    // 修改处理
    public function update(FangOwnerRequest $request, FangOwner $fangowner)
    {
        $data = $request->except('_token', '_method', 'file');
        $fangowner->update($data);
        return redirect(route('admin.fangowner.index'))->with('success', '修改成功');
    }

    // 删除
    public function destroy(FangOwner $fangowner)
    {
        $fangowner->delete();
        return ['status' => 0, 'msg' => '删除成功'];
    }
}
