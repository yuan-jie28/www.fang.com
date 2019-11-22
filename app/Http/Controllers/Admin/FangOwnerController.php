<?php
// 房东管理
namespace App\Http\Controllers\Admin;

use App\Http\Requests\FangOwnerRequest;
use App\Models\FangOwner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FangOwnerController extends Controller
{
    // 列表
    public function index()
    {
        //
    }

    // 添加显示
    public function create()
    {
        return view('admin.fangowner.create');
    }

    // 添加处理
    public function store(FangOwnerRequest $request)
    {
        $data = $request->except(['file','_token']);
        FangOwner::create($data);
        return redirect(route('admin.fangowner.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FangOwner  $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function show(FangOwner $fangOwner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FangOwner  $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function edit(FangOwner $fangOwner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FangOwner  $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FangOwner $fangOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FangOwner  $fangOwner
     * @return \Illuminate\Http\Response
     */
    public function destroy(FangOwner $fangOwner)
    {
        //
    }
}
