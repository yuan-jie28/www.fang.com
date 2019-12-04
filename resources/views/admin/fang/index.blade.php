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
        <span class="c-gray en">&gt;</span> 房源管理
        <span class="c-gray en">&gt;</span> 房源列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <div class="page-container">
        <div class="text-c"> 日期范围：
            <input value="{{ request()->get('st') }}" type="text" onfocus="WdatePicker({})" name="st"
                   class="input-text Wdate" style="width:120px;">
            -
            <input value="{{ request()->get('et') }}" type="text" onfocus="WdatePicker({})" name="et"
                   class="input-text Wdate" style="width:120px;">
            <input value="{{ request()->get('kw') }}" type="text" class="input-text" style="width:250px"
                   placeholder="输入搜索的账号" id="kw">
            <button type="button" class="btn btn-success radius" onclick="searchBtn()">
                <i class="Hui-iconfont">&#xe665;</i> 搜索一下
            </button>
        </div>

        @include('admin.public.msg')

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                <a href="{{ route('admin.fang.create') }}" class="btn btn-primary radius">
                    <i class="Hui-iconfont">&#xe600;</i> 添加房源
                </a>
            </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
                <thead>
                <tr class="text-c">
                    <th width="40">ID</th>
                    <th width="70">房源名称</th>
                    <th width="70">小区地址</th>
                    <th width="100">房源地址</th>
                    <th width="40">房源业主</th>
                    <th width="220">配套设施</th>
                    <th width="40">是否推荐</th>
                    <th width="40">房源状态</th>
                    <th width="140">操作</th>
                </tr>
                </thead>
                @foreach($data as $item)
                    <tr id="juzhong">
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->fang_name }}</td>
                        <td>{{ $item->fang_xiaoqu }}</td>
                        <td>{{ $item->fang_addr }}</td>
                        <td>{{ $item->fangowner->name }}</td>
                        <td>{{ $item->getAttrIdByName(explode(',',$item->fang_config)) }}</td>
                        <td>{{ $item->is_recommend == '0' ? '否' : '是'}}</td>
                        <td>{{ $item->fang_status == '0' ? '待租' : '已租'}}</td>
                        <td>
                            {!! $item->editBtn('admin.fang.edit') !!}
                            {!! $item->delBtn('admin.fang.destroy') !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{-- 分页 --}}
        {{ $data->appends(request()->except(['page']))->links() }}
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/laypage/1.2/laypage.js"></script>
    <script>
        // 删除文章
        // 事件委托
        $('.table-sort').on('click', '.deluser', function () {
            // 请求地址
            let url = $(this).attr('href');
            layer.confirm('您真的要删除此用户吗？', {
                btn: ['确认删除', '再想一下']
            }, () => {
                // 使用fetch来实现异步ajax  默认返回promise
                fetch(url, {
                    // 指定请求的类型
                    method: 'delete',
                    // 指定header
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        // json字符串传递数据，需要此头信息
                        'content-type': 'application/json'
                    },
                    // 数据
                    body: JSON.stringify({name: 1})
                }).then(res => {
                    return res.json();
                }).then(ret => {
                    layer.msg('删除成功', {icon: 1, time: 1000}, () => {
                        $(this).parents('tr').remove();
                    })
                });
            });
            return false;
        });
    </script>
@endsection

