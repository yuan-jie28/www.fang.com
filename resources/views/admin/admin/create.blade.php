@extends('admin.public.main')

@section('cnt')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 用户中心
        <span class="c-gray en">&gt;</span> 添加用户
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
           href="javascript:location.replace(location.href);" title="刷新"><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <article class="page-container">

        {{-- 错误信息 --}}
        @include('admin.public.msg')

        <form action="{{ route('admin.user.store') }}" method="post" class="form form-horizontal" id="form-admin-add">
            @csrf
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>账号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    {{-- old函数是表单验证不通过后，保留原数据  old('表单名称')--}}
                    <input type="text" class="input-text" value="{{ old('username') }}" id="adminName" name="username">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>真实姓名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('truename') }}" name="truename">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>性别：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="sex" type="radio" value="先生" checked>
                        <label for="sex-1">先生</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" value="女士" name="sex">
                        <label for="sex-2">女士</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>手机：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('phone') }}" name="phone">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>邮箱：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{ old('email') }}" name="email">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>角色：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    {{-- 显示角色列表 --}}
                    @foreach($roleData as $id=>$name)
                        <div class="radio-box">
                            <label>
                                <input name="role_id" type="radio" value="{{ $id }}">
                                {{ $name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="password" class="input-text" name="password">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>确认密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="password" class="input-text" name="password_confirmation">
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input class="btn btn-primary radius" type="submit" value="添加用户">
                </div>
            </div>
        </form>
    </article>
@endsection
@section('js')
    <script>
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
    </script>

@endsection
