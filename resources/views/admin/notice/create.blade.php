@extends('admin.public.main')

@section('css')
    <link rel="stylesheet" href="{{ staticAdminWeb() }}lib/webuploader/0.1.5/webuploader.css">
@endsection

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 预约管理
        <span class="c-gray en">&gt;</span> 添加预约
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>

    @include('admin.public.msg')

    <article class="page-container" id="appform">
        <form class="form form-horizontal" id="form-article-add">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>房东：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
                <!-- 有默认值，才会根据value==默认值则选中 -->
				<select v-model="frmData.fangowner_id" class="select">
                    <template v-for="item in fangownerData">
                        <option :value="item.id">@{{ item.name }}</option>
                    </template>
				</select>
				</span></div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>租客：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select v-model="frmData.renting_id" class="select">
                    <template v-for="item in rentingData">
                        <option :value="item.id">@{{ item.truename }}</option>
                    </template>
				</select>
				</span></div>
            </div>

            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>预约时间：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" ref="datepicker" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"
                           @blur="getDt" class="input-text" style="width: 260px;" v-model="frmData.dtime">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">文章摘要：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea class="textarea" v-model="frmData.cnt"></textarea>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <button class="btn btn-primary radius" type="button" @click="dopost">添加预约</button>
                </div>
            </div>
        </form>
    </article>

@endsection

@section('js')
    <!-- 时间控制 -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <!-- 引入vue -->
    <script src="/js/vue.js"></script>
    <script>
        new Vue({
            el: '#appform',
            // 初始化数据
            data: {
                // 房东数据
                fangownerData: [],
                // 租客数据
                rentingData: [],
                // 表单数据
                frmData: {
                    // 房东给定默认值为1
                    fangowner_id: 1,
                    // 租客
                    renting_id: 1,
                    _token: "{{ csrf_token() }}"
                }
            },
            // 生命周期函数
            mounted() {
                $.get('{{ route("admin.notice.create") }}').then(([fangownerData, rentingData]) => {
                    this.fangownerData = fangownerData;
                    this.rentingData = rentingData;
                })
            },
            methods: {
                // 表单提交
                dopost() {
                    let url = "{{ route('admin.notice.store') }}";
                    $.post(url, this.frmData).then(ret => {
                        if (ret.status == 0) {
                            // 添加数据成功
                            layer.msg('添加成功', {icon: 1, timeout: 1500}, () => {
                                location.href = ret.url;
                            })
                        } else {
                            // 验证不通过的提示
                            layer.msg(ret.msg, {icon: 2, timeout: 1500})
                        }
                    });
                },
                // 时间
                getDt() {
                    // vue获取dom对象ref 引用
                    this.frmData.dtime = this.$refs['datepicker'].value;
                }
            }
        });
    </script>
@endsection
