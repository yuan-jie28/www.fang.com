<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <link href="{{ staticAdminWeb() }}static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ staticAdminWeb() }}static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css"/>
    <link href="{{ staticAdminWeb() }}static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="{{ staticAdminWeb() }}lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css"/>
    <title>后台登录</title>
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value=""/>
<div class="header"></div>
<div class="loginWraper">
    <div id="loginform" class="loginBox">
        <form class="form form-horizontal" action="{{ route('admin.login') }}" method="post">
            {{-- laravel5.5以上可以这样写，5.5之前这样写 {{ csrf_field() }} --}}
            @csrf
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
                <div class="formControls col-xs-8">
                    <input name="username" type="text" placeholder="账户" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                <div class="formControls col-xs-8">
                    <input name="password" type="password" placeholder="密码" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input class="input-text size-L" name="captcha" type="text" placeholder="验证码"
                           onblur="if(this.value==''){this.value='验证码:'}"
                           onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
                    <img id="code" src="{{ captcha_src() }}"> <a href="#" onclick="changeimg(this)">看不清，换一张</a> </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input name="" type="submit" class="btn btn-success radius size-L"
                           value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
                    <input name="" type="reset" class="btn btn-default radius size-L"
                           value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
        </form>

        {{-- 表单验证提示  blade模板包含，把共用的html提取到外部，方便日后共用 --}}
        @include('admin.public.msg')

    </div>
</div>
<div class="footer">Copyright 你的公司名称 by H-ui.admin v3.1</div>
<script type="text/javascript" src="{{ staticAdminWeb() }}lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ staticAdminWeb() }}static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="http://php-acad.28sjw.com/Statics/Assets/js/jquery.min-3.2.1.js"></script>
<script>
    $(function(){
       // 给 图片 绑定点击事件
        var url = $('img').attr('src');
        $('img').click(function(){
            $(this).attr('src',url + '&_a=' + Math.random());
        });
    });
    function changeimg(){
        var url = $('#code').attr('src');
        $('#code').attr('src',url + '&_a=' + Math.random());
    }
</script>
</body>
</html>
