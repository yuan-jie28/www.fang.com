<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fangattr;
use App\Models\FangOwner;
use App\Models\Fang;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Requests\FangRequest;

class FangController extends BaseController
{
    // 列表
    public function index()
    {
        // 调用是使用了with进行和房东之间的模型关联
        $data = Fang::with('fangowner')->paginate($this->pagesize);
        return view('admin.fang.index', compact('data'));
    }

    // 添加显示
    public function create()
    {
        // 初始获取省份信息
        $pData = $this->getCity();
        // 房源属性
        $attrData = Fangattr::all()->toArray();
        // 以字段名为下标创建多层数组
        $attrData = subTree2($attrData);
        // 读取房东
        $fData = FangOwner::all();
        return view('admin.fang.create', compact('pData', 'attrData', 'fData'));
    }

    // 获取城市
    public function getCity($pid = 0)
    {
        $pid = $pid == 0 ? request()->get('pid', 0) : $pid;
        // 地区三级联动
        return City::where('pid', $pid)->get();
    }

    // 添加处理
    public function store(FangRequest $request)
    {
        $data = $request->except('_token', 'file');
//        $data['fang_config'] = implode(',',$data['fang_config']);
        Fang::create($data);
        return redirect(route('admin.fang.index'));
    }

    // 修改显示
    public function edit(Fang $fang)
    {
        // 初始化获取省份信息
        $pData = $this->getCity();
        // 获取城市信息
        $cData = $this->getCity($fang->fang_province);
        // 获取区/县信息
        $rData = $this->getCity($fang->fang_city);

        // 房源属性
        $attrData = Fangattr::all()->toArray();
        // 以字段名为下标创建多层数组
        $attrData = subTree2($attrData);
        // 读取房东
        $fData = FangOwner::all();
        return view('admin.fang.edit',compact('fang','pData','cData','rData','attrData','fData'));
    }

    // 修改处理
    public function update(FangRequest $request, Fang $fang)
    {
//        dd(11111);
        // dump($request->all());exit;

        $a=$fang->update($request->except(['_token','file','_method','fang_addr2']));
        return redirect(route('admin.fang.index'));
    }

    // 删除
    public function destroy(Fang $fang)
    {
        //
    }
}
