@extends('admin.public.main')

@section('css')
    <style>
        #juzhong td{
            /*居中*/
            text-align:center;
        }
    </style>
@endsection

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 房源属性
        <span class="c-gray en">&gt;</span> 房源属性列表
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
                <a href="{{ route('admin.fangattr.create') }}" class="btn btn-primary radius">
                    <i class="Hui-iconfont">&#xe600;</i> 添加房源属性
                </a>
            </span>
        </div>
        <div class="mt-20" id="app">
            <table class="table table-border table-bordered table-hover table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="80">ID</th>
                    <th width="100">属性名称</th>
                    <th width="40">图标</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                {{-- 列表数据 --}}
                <tr v-for="item in items" id="juzhong">
                    <td v-text="item.id"></td>
                    <td :style="'padding-left:'+(item.level*20)+'px'">@{{ item.name }}</td>
                    <td>
                        <img :src="item.icon" style="width: 100px;">
                    </td>
                    <td v-html="item.actionBtn"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/laypage/1.2/laypage.js"></script>
    <script type="text/javascript" src="/js/vue.js"></script>
    <script>
        const _token = "{{ csrf_token() }}";
        const app = new Vue({
            el: '#app',
            data: {
                // 房源列表
                items: []
            },
            mounted() {
                $.get("{{ route('admin.fangattr.index') }}").then(ret => {
                    // 数据代理  Object.defineProperty(obj,{set,get})
                    this.items = ret;
                })
            }
        })
        $('.table-sort').on('click', '.deluser', function () {
            return false;
        })

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
