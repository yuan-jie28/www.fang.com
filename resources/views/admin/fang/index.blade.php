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
                <a class="btn btn-danger radius">
                    <i class="Hui-iconfont">&#xe6e2;</i> 批量删除
                </a>
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
    <!-- 引入datatables类库文件 -->
    <script src="{{ staticAdminWeb() }}lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script>
        // 选择对应的类选择器
        // 实例化
        const datatable = $('.table-sort').dataTable({
            // 页码修改
            lengthMenu: [10, 20, 30, 50, 100],
            // 指定不排序
            columnDefs: [
                // 索引下标为4的不进行排序
                {targets: [4], orderable: false}
            ],
            // 初始化排序
            order: [[{{ request()->get('field') ?? 0 }}, '{{ request()->get("order") ?? "desc" }}']],
            // 从第几条数据开启显示
            displayStart: {{ request()->get('start') ?? 0 }},
            // 取消默认搜索 客户端分页可以保留
            searching: false,
            // 开启服务器端分页
            serverSide: true,
            // 进行ajax配置
            ajax: {
                // 请求地址,请求地址和显示模板页面一个url，通过请求的类型来区别，是否是ajax请求
                url: '{{ route('admin.article.index') }}',
                type: 'GET',
                data: function (ret) {
                    // 获取表单数据
                    ret.kw = $.trim($('#kw').val())
                }
            },
            // 根据服务器端返回的数据显示
            // 定义表格中每列中数据的显示
            columns: [
                {data: 'id', className: 'text-c'},
                {data: 'title'},
                {data: 'cate.cname'},
                {data: 'updated_at'},
                // 操作数据源中没有对应的数据
                {data: 'actionBtn', className: 'text-c'}
            ],
            // 生成对应行时数据对应回事件
            // row 当前行的dom对象 data当前行的数据
            createdRow: function (row, data) {
                // 查找当行中最后一列元素对象
                // 把dom对象转为jquery对象
                /*var td = $(row).find('td:last-child')
                // 动态HTML
                var html = `<a href="###" class="label label-secondary radius">修改</a>`;
                td.html(html)*/
            }
        });

        // 搜索
        function searchBtn() {
            datatable.api().ajax.reload();
        }

        // 删除文章
        // 事件委托
        $('.table-sort').on('click', '.deluser', function () {
            // 请求地址
            let url = $(this).attr('href');
            // 使用fetch来实现异步ajax 默认返回promise
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

            return false;
        })
    </script>
@endsection

