@extends('admin.public.main')

@section('css')
    <!-- webuploader上传样式 -->
    <link rel="stylesheet" href="{{ staticAdminWeb() }}lib/webuploader/0.1.5/webuploader.css">
    <style>
        #imgbox img {
            margin: 5px;
            width: 150px;
        }
    </style>
@endsection

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 房东管理
        <span class="c-gray en">&gt;</span> 添加房东
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <article class="page-container">

        {{-- 错误信息 --}}
        @include('admin.public.msg')

        <form action="{{ route('admin.fangowner.store') }}" method="post" class="form form-horizontal"
              id="form-node-add">
            @csrf
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>房东姓名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('name') }}" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>性别：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <label>
                            <input name="sex" type="radio" value="男" checked>男
                        </label>
                    </div>
                    <div class="radio-box">
                        <label>
                            <input name="sex" type="radio" value="女">女
                        </label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>房东年龄：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('age') }}" name="age">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>手机号码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('phone') }}" name="phone">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>身份证号码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('card') }}" name="card">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>家庭住址：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('address') }}" name="address">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>邮箱：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('email') }}" name="email">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">* </span>身份证照片：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div class="uploader-thum-container">
                        <div id="filePicker">选择图片</div>
                        <input type="hidden" name="pic" id="pic">
                        {{-- 上传图片显示区域 --}}
                        <div id="imgbox"></div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="添加房东">
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
                // 允许多张图片上传
                multiple: true
            },
            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            // 表单传额外值
            formData: {
                _token: "{{ csrf_token() }}",
                // 上传的节点名称
                node: 'fangowner'
            },
            // 上传表单名称
            fileVal: 'file'
        });
        // 回调方法监听
        uploader.on('uploadSuccess', function (file, {url}) {
            // 上传图片地址，因为是多图上传，进行字符串的拼接
            // 获取表单中的数据
            let val = $('#pic').val();
            $('#pic').val(val + '#' + url);
            // 显示图片所用
            var imgObj = $('<img />');
            imgObj.attr('src', url);
            $('#imgbox').append(imgObj);
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
                age: {
                    // 验证规则就是自定义名称
                    required: true,
                    digits: true,
                    min: 18,
                    max: 110
                },
                phone: {
                    required: true,
                    checkPhone: true
                },
                card: {
                    required: true,
                    checkCard: true
                },
                address: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
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

        //自定义jquery-validate验证器
        jQuery.validator.addMethod("checkPhone", function (value, element) {
            var reg = /^1[3-9]\d{9}$/;
            return this.optional(element) || (reg.test(value));
        }, "您输入的手机号码不合法");
        jQuery.validator.addMethod("checkCard", function (value, element) {
            var card = value.replace(' ', '');
            var len = card.length;
            var bool = len == 18 ? true : false;
            return this.optional(element) || bool;
        }, "您输入的身份证号码不合法");
    </script>

@endsection
