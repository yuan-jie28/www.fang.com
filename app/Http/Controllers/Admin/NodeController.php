<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use App\Models\Admin;
use Illuminate\Http\Request;

class NodeController extends BaseController
{
    // 权限列表
    public function index()
    {
        // 转为数组
        $data = Node::all()->toArray();
        // 递归是针对数组所在的位置把数据转为数组
        $data = treeLevel($data);
        return view('admin.node.index', compact('data'));
    }

    // 添加权限显示
    public function create()
    {
        // 读取 pid=0的数据  pluck取一列数据
        // 参1  数组的值
        // 参2  数组的下标
        $data = Node::where('pid', 0)->pluck('name', 'id')->toArray();

        // 在数组顶部添加一个值
        // array_unshift($data,'==顶级==');
        $data[0] = '==顶级==';
        // 因为sort是按照序列排序，和id值无关，而ksort是按照键值排序，所以可以解决pid的问题
        ksort($data);
        //dump($data);
        return view('admin.node.create', compact('data'));
    }

    // 添加处理
    public function store(Request $request)
    {
        // 表单验证
        $this->validate($request, [
            'name' => 'required'
        ]);

        // 添加数据入库
        Node::create($request->except(['_token']));
        return redirect(route('admin.node.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    // 修改显示
    public function edit($id)
    {
        // 获取当前用户信息
        $data = Node::find($id);
        return view('admin.node.edit', compact('data'));
    }

    // 修改处理
    public function update(Request $request, Node $node)
    {
        // 表单验证
        $data = $this->validate($request, [
            'name' => 'required|unique:nodes,name,' . $node->id,
            'route_name' => 'required',
            'is_menu' => 'required|in:0,1',
        ]);
        // 修改
        $node->update($data);

        return redirect(route('admin.node.index'))->with('success', '修改权限成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
