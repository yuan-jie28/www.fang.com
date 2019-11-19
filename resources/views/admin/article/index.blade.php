@extends('admin.public.main')
@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 文章管理
        <span class="c-gray en">&gt;</span> 文章列表
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
                   placeholder="输入搜索的账号" name="kw" id="kw">
            <button type="button" class="btn btn-success radius" onclick="searchBtn()">
                <i class="Hui-iconfont">&#xe665;</i> 搜索
            </button>
        </div>

        @include('admin.public.msg')

        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                <a class="btn btn-danger radius" onclick="deleteAll()">
                    <i class="Hui-iconfont">&#xe6e2;</i> 批量删除
                </a>
                <a href="{{ route('admin.article.create') }}" class="btn btn-primary radius">
                    <i class="Hui-iconfont">&#xe600;</i> 添加文章
                </a>
            </span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-hover table-sort table-responsive">
                <thead>
                <tr class="text-c">
                    <th width="80">ID</th>
                    <th width="80">标题</th>
                    <th width="80">分类</th>
                    <th width="120">更新时间</th>
                    <th width="120">操作</th>
                </tr>
                </thead>
                {{-- 开启了服务器端分页，就不用在前端循环输出 --}}
            </table>
        </div>
    </div>
@endsection

@section('js')
    <!-- 引入datatables类库文件 -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script>

        // 选择对应的类选择器
        // 实例化
        const datatable = $('.table-sort').dataTable({
            // 页码修改
            lengthMenu: [10, 20, 30, 50, 100],
            // 指定不排序
            columnDefs: [
                // 索引下标为4的不进行排序,也就是不让修改与删除按钮进行排序
                {targets: [4], orderable: false}
            ],
            // 初始化排序
            order: [[{{ request()->get('field') ?? 0 }},'{{ request()->get("order") ?? "desc"}}']],
            // 从第几条数据开启显示
            displayStart: {{ request()->get('start') ?? 0 }},
            // 取消默认搜索  客户端分页可以保留
            searching: false,
            // 开启服务端分页，
            // 因为客户端分页有缺点，一次性读出所有的数据，对后端服务器压力太大，同时也因为是所有的数据都循环输出，在前端页面的DOM操作也会效率很慢
            serverSide: true,
            // 进行ajax配置
            ajax: {
                // 请求地址，请求地址和显示模板页面一个url，通过请求的类型来区别，是否是Ajax请求
                url: '{{ route('admin.article.index') }}',
                type: 'GET',
                data: function (ret) {
                    // 获取表单数据
                    ret.kw = $.trim($('#kw').val())
                }
            },
            // 根据服务器端返回的数据显示
            // 定义表格中每列中的数据显示   columns要对tr中的td单元格中的内容进行数据填充
            columns: [
                // 注意：如果data接收类似a或b的信息，实际服务器没有返回该信息，那么一定要同时设置defaultContent属性，否则报错
                // 总的数量与表格的列的数量一致，不多也不少
                // 字段名称与sql查询出来的字段时要保持一致，就是服务器返回数据对应的字段名称
                // defaultContent 和 className 可选参数
                {'data': 'id', 'className': 'text-c'},
                {'data': 'title'},
                {'data': 'cate.cname'},
                {'data': 'created_at'},
                {'data': 'actionBtn', 'className': 'text-c'}
            ],
            // 生成对应行时数据对应回调时间
            // row 是当前行的dom对象  data是当前行的数据
            createdRow: function (row, data) {
                // 查找当前行中最后一列元素对象
                // 把dom对象转为jQuery对象
                // var td = $(row).find('td:last-child');
                // // 动态HTML  也就是将显示出来的操作汉字转为按钮
                // var html = `<a herf="##" class="btn btn-secondary-outline radius">修改</a>`;
                // td.html(html);
            }
        });

        // 搜索
        function searchBtn() {
            datatable.api().ajax.reload();
        }

        // 删除文章
        // 事件委托
        $('.table-sort').on('click', '.deluser',function(){
            // 请求地址
            let url = $(this).attr('href');
            // 使用fetch来实现异步ajax  默认返回promise
            fetch(url,{
                // 指定请求的类型
                method: 'delete',
                // 指定header
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    // json字符串传递数据，需要此头信息
                    'content-type': 'application/json'
                },
                // 数据
                body: JSON.stringify({name: 1})
            }).then(res => {
                return res.json();
            }).then(ret => {
                layer.msg('删除成功',{icon: 1, time: 1000}, () => {
                    $(this).parents('tr').remove();
                })
            });

            return false;
        });

        const _token = "{{ csrf_token() }}";
        // 此时第一次使用回调函数时，不能使用箭头函数，因为会破坏this的指向，laravel框架已经自动添加了this指向
        $('.deluser').click(function () {
            // 发起请求的地址
            var url = $(this).attr('data-href');
            // 点击删除按钮，会询问是否删除
            layer.confirm('您真的要删除此用户吗？', {
                btn: ['确认删除', '再想一下']
            }, () => {
                // 确认删除，此时必须使用箭头函数，来保证this的指向不变
                $.ajax({
                    url,
                    type: 'delete',
                    data: {_token}
                }).then(ret => {
                    // 把当前点击的行给删除了  js的dom操作
                    $(this).parents('tr').remove();
                    // 让所有的layer插件弹窗都关闭
                    // layer.closeAll();
                    // 提示  自动关闭一个弹框
                    layer.msg(ret.msg, {icon: 1, time: 1000}, function () {
                        // 自动刷新页面
                        location.reload();
                    });

                });
            });
            // jquery中取消默认行为
            return false;
        });

        // 全选删除
        function deleteAll() {
            // 选择选中的复选框
            var inputs = $('input[name="ids[]"]:checked');
            // 用户id
            var ids = [];
            inputs.map((key, item) => {
                ids.push($(item).val());
            });
            $.ajax({
                url: '{{ route('admin.user.delall') }}',
                type: 'delete',
                data: {
                    _token,
                    ids
                }
            }).then(ret => {
                inputs.map((key, item) => {
                    $(item).parents('tr').remove();
                });
            });
        }

    </script>
@endsection
