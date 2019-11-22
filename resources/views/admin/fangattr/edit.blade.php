@extends('admin.public.main')

@section('css')
    <link rel="stylesheet" href="{{ staticAdminWeb() }}lib/webuploader/0.1.5/webuploader.css">
    <style>
        .imgbox {
            width: 200px;
            height: 150px;
            margin-left: 100px;
            position: relative;
        }

        .imgbox img {
            height: 100%;
            width: 100%;
            border-radius: 5px;
        }

        .imgbox p {
            position: absolute;
            right: 5px;
            top: 2px;
            font-weight: bold;
            color: red;
            font-size: 25px;
            cursor: pointer;
        }
    </style>
@endsection

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 房源属性
        <span class="c-gray en">&gt;</span> 修改房源属性
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <article class="page-container">

        {{-- 错误信息 --}}
        @include('admin.public.msg')

        <form action="{{ route('admin.fangattr.update',$fangAttr) }}" method="post" class="form form-horizontal" id="form-admin-add">
            @csrf
            {{--{{ method_field('PUT') }}--}}
            @method('PUT')
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red"></span>顶级属性：</label>
                <div class="formControls col-xs-8 col-sm-9"><span class="select-box">
                       <select name="pid" id="pid" class="select">
                           @foreach($data as $id=>$name)
                               <option value="{{ $id }}">{{ $name }}</option>
                           @endforeach
                       </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>属性名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ $fangAttr->name }}" id="adminName" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">字段名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ $fangAttr->field_name }}" name="field_name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">图标：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div class="uploader-thum-container">
                        <div id="filePicker">选择图片</div>
                        <input type="hidden" name="icon" id="pic" value="{{ $fangAttr->icon }}">
                        <div class="imgbox">
                            <img src="{{ $fangAttr->icon }}" id="showpic">
                            <p onclick="delpic({{ $fangAttr->id }},'{{ $fangAttr->icon }}')">X</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="修改房源属性">
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


        /**
         *  删除图片操作
         *  @param id 文章ID
         *  @param src 封面地址
         */
        function delpic(id, src) {
            $.get('{{ route("admin.fangattr.delfile") }}',{id,src}).then(ret => {
                $('#pic').val('');
                $('#showpic').attr('src','');
                $('.imgbox').slideUp('slow');
            });
        }

        // 表单验证
        $("#form-article-add").validate({
            rules: {
                title: {
                    required: true
                },
                desn: {
                    required: true
                }
            },
            onkeyup: false,
            success: "valid",
            submitHandler: function (form) {
                form.submit();
            }
        });

        // 异步文件上传
        var uploader = WebUploader.create({
            // 自动上传
            auto: true,
            // swf文件路径
            swf: '{{ staticAdminWeb() }}lib/webuploader/0.1.5/Uploader.swf',
            {{--// 文件接收服务端   路由存在，上传图片处理  {{ route('admin.article.upfile') }}--}}
            server: '{{ route('admin.base.upfile') }}',
            // 选择文件的按钮     此按钮必须和HTML中的按钮ID名称一致
            pick: '#filePicker',
            // 不压缩image，默认如果是jpeg，文件上传前会压缩一下再上传
            resize: false,
            // 表单传额外值     csrf验证是所用
            formData: {_token: "{{ csrf_token() }}",node:"fangattr"},
            // 上传表单名称
            fileVal: 'file'
        });
        // 回调方法监听
        uploader.on('uploadSuccess', function (file, {url}) {
            $('#pic').val(url);
            $('#showpic').attr('src', url);
            $('.imgbox').slideDown('slow');
        });
    </script>
@endsection
