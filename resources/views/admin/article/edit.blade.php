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
        <span class="c-gray en">&gt;</span> 文章管理
        <span class="c-gray en">&gt;</span> 文章修改
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>

    @include('admin.public.msg')

    <article class="page-container">
        <form class="form form-horizontal" id="form-article-add" method="post"
              action="{{ route('admin.article.update',['id'=>$article->id,'url'=>$url_query]) }}">
            @csrf
            @method('PUT')
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>文章标题：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ $article->title }}" placeholder="" name="title">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>分类栏目：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select name="cid" class="select">
                    @foreach($cateData as $item)
                        <option value="{{ $item['id'] }}" @if($item['id']==$article->cid) selected @endif>{{ $item['html'] }}{{ $item['cname'] }}</option>
                    @endforeach
				</select>
				</span></div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>文章摘要：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea name="desn" cols="" rows="" class="textarea">{{ $article->desn }}</textarea>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">封面图：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div class="uploader-thum-container">
                        <div id="filePicker">选择图片</div>
                        <input type="hidden" name="pic" id="pic" value="{{ $article->pic }}">
                        <div class="imgbox">
                            <img src="{{ $article->pic }}" id="showpic">
                            <p onclick="delpic({{ $article->id }},'{{ $article->pic }}')">X</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">文章内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea name="body" id="body">{{ $article->body }}</textarea>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <button class="btn btn-primary radius" type="submit">修改文章</button>
                </div>
            </div>
        </form>
    </article>
@endsection

@section('js')
    <!-- 引入 ueditor js类库 -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/ueditor/1.4.3/ueditor.config.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/ueditor/1.4.3/ueditor.all.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
    <!-- 引入webuploader插件 类库JS -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/webuploader/0.1.5/webuploader.min.js"></script>
    <!-- 表单前端验证插件 jquery validate -->
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript"
            src="{{ staticAdminWeb() }}lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="{{ staticAdminWeb() }}lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script>
        /**
         *  删除图片操作
         *  @param id 文章ID
         *  @param src 封面地址
         */
        function delpic(id, src) {
            $.get('{{ route("admin.article.delfile") }}',{id,src}).then(ret => {
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
            server: '{{ route('admin.article.upfile') }}',
            // 选择文件的按钮     此按钮必须和HTML中的按钮ID名称一致
            pick: '#filePicker',
            // 不压缩image，默认如果是jpeg，文件上传前会压缩一下再上传
            resize: false,
            // 表单传额外值     csrf验证是所用
            formData: {_token: "{{ csrf_token() }}"},
            // 上传表单名称
            fileVal: 'file'
        });
        // 回调方法监听
        uploader.on('uploadSuccess', function (file, {url}) {
            $('#pic').val(url);
            $('#showpic').attr('src', url);
            $('.imgbox').slideDown('slow');
        });

        // 富文本
        var ue = UE.getEditor('body', {
            // 初始化高度
            initialFrameHeight: 500
        });
    </script>
@endsection
