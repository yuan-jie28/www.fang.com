@extends('admin.public.main')

@section('css')
    <link rel="stylesheet" href="{{ staticAdminWeb() }}lib/webuploader/0.1.5/webuploader.css">
@endsection

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 房源属性
        <span class="c-gray en">&gt;</span> 添加房源属性
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <article class="page-container">

        {{-- 错误信息 --}}
        @include('admin.public.msg')

        <form action="{{ route('admin.fangattr.store') }}" method="post" class="form form-horizontal"
              id="form-node-add">
            @csrf
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red"></span>顶级属性：</label>
                <div class="formControls col-xs-8 col-sm-9"><span class="select-box">
                       <select name="pid" id="pid" class="select">
                           @foreach($data as $id=>$name)
                               <option value="{{ $id }}" @if($id == 0) selected @endif>{{ $name }}</option>
                           @endforeach
                       </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>属性名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('name') }}" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">字段名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('field_name') }}"  name="field_name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">图标：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div class="uploader-thum-container">
                        <div id="filePicker">选择图片</div>
                        <input type="hidden" name="icon" id="pic">
                        <div class="imgbox">
                            <img src="" style="width: 100px;" id="showpic">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="添加房源属性">
                </div>
            </div>
        </form>
    </article>
@endsection
@section('js')
    <!-- 引入webuploader插件 类库JS -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/webuploader/0.1.5/webuploader.min.js"></script>
    <!-- 表单前端验证插件 jquery validate -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script>
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

        // 异步文件上传
        var uploader = WebUploader.create({
            // 自动上传
            auto: true,
            // swf文件路径
            swf: '{{ staticAdminWeb() }}lib/webuploader/0.1.5/Uploader.swf',
            // 文件接收服务端
            server: '{{ route('admin.base.upfile') }}',
            // 选择文件的按钮
            pick: {
                id: '#filePicker',
                // 只允许单张图片
                multiple: false
            },
            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            // 表单传额外值
            formData: {
                _token: "{{ csrf_token() }}",
                // 上传的节点名称
                node: 'fangattr'
            },
            // 上传表单名称
            fileVal: 'file'
        });
        // 回调方法监听
        uploader.on('uploadSuccess', function (file, {url}) {
            // 表单，用户于提交数据所用
            $('#pic').val(url);
            // 显示图片所用
            $('#showpic').attr('src', url);
        });

        // 自定义jquery-validate验证器
        jQuery.validator.addMethod("fieldName", function (value, element) {
            // 获取房源属性下拉列表中的元素的值
            var bool = $('#pid').val() == 0 ? false : true;
            // 正则 \w 0-9a-zA-Z_
            var reg = /[a-zA-Z_]+/;
            return bool || (reg.test(value));
        }, "选择顶级属性请一定要填写对应的字段名称");

        // 表单验证
        $("#form-node-add").validate({
            // 规则
            rules: {
                // 表单名
                name: {
                    // 规则名  true/false
                    required: true
                },
                field_name: {
                    // 验证规则就是自定义名称
                    fieldName: true
                }
            },
            // 回车取消
            onkeyup: false,
            // 成功时样式
            success: "valid",
            // 验证通过后，处理回调函数
            submitHandler: function (form) {
                // 验证通过，使用js触发表单提交事件
                form.submit();
            }
        });

    </script>

@endsection
