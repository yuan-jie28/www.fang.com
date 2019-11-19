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
        <form>
            <div class="text-c"> 日期范围：
                <input value="{{ request()->get('st') }}" type="text" onfocus="WdatePicker({})" name="st"
                       class="input-text Wdate" style="width:120px;">
                -
                <input value="{{ request()->get('et') }}" type="text" onfocus="WdatePicker({})" name="et"
                       class="input-text Wdate" style="width:120px;">
                <input value="{{ request()->get('kw') }}" type="text" class="input-text" style="width:250px"
                       placeholder="输入搜索的账号" name="kw">
                <button type="submit" class="btn btn-success radius" id="" name="">
                    <i class="Hui-iconfont">&#xe665;</i> 搜索
                </button>
            </div>
        </form>

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
                <tbody>
                {{-- 循环输出内容 --}}
                @foreach($data as $item)
                    <tr class="text-c">
                        <td>{{ $item->id }}</td>
                        <td class="text-l">{{ $item->title }}</td>
                        {{-- 模型关系 --}}
                        <td>{{ $item->cate->cname }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td class="f-14 td-manage">
                            {!! $item->editBtn('admin.user.edit') !!}
                            {!! $item->delBtn('admin.user.destroy') !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
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
        $('.table-sort').dataTable({
            // 页码修改
            aLengthMenu: [10, 20, 30, 50, 100],
            // 指定不排序
            columnDefs: [
                // 索引下标为4的不进行排序,也就是不让修改与删除按钮进行排序
                {targets: [4], orderable: false}
            ],
            // 初始化排序
            order: [[0, 'desc']],
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
