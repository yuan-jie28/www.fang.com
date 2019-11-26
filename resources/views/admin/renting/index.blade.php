@extends('admin.public.main')

@section('css')
    <style>
        #juzhong td {
            /*居中*/
            text-align: center;
        }
    </style>
@endsection

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 租客管理
        <span class="c-gray en">&gt;</span> 租客列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <div class="page-container">
        <div class="text-c"> 日期范围：
            <input value="{{ request()->get('st') }}" type="text" onfocus="WdatePicker({})" name="st" class="input-text Wdate" style="width:120px;">
            -
            <input value="{{ request()->get('et') }}" type="text" onfocus="WdatePicker({})" name="et" class="input-text Wdate" style="width:120px;">
            <input value="{{ request()->get('kw') }}" type="text" class="input-text" style="width:250px" placeholder="输入搜索的账号" id="kw">
            <button type="button" class="btn btn-success radius" onclick="searchBtn()">
                <i class="Hui-iconfont">&#xe665;</i> 搜索一下
            </button>
        </div>

        @include('admin.public.msg')

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                <a class="btn btn-danger radius">
                    <i class="Hui-iconfont">&#xe6e2;</i> 批量删除
                </a>
                <a href="##" class="btn btn-primary radius">
                    <i class="Hui-iconfont">&#xe600;</i> 添加租客
                </a>
            </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
                <thead>
                <tr class="text-c">
                    <th width="80">ID</th>
                    <th width="100">真实姓名</th>
                    <th width="100">昵称</th>
                    <th width="120">手机号码</th>
                    <th width="120">操作</th>
                </tr>
                </thead>
                @foreach($data as $item)
                    <tr id="juzhong">
                        <td>{{$item->id}}</td>
                        <td>{{ $item->truename }}</td>
                        <td>{{ $item->nickname }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>
                            {!! $item->editBtn('admin.notice.edit') !!}
                            {!! $item->delBtn('admin.notice.destroy') !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{ $data->links() }}
    </div>
@endsection

@section('js')
@endsection

