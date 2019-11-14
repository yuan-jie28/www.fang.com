{{-- 显示表单验证错误信息--}}
@if($errors->any())
    <div class="Huialert Huialert-danger"><i class="Hui-iconfont">&#xe60d;</i>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif

{{-- 闪存信息的读取  成功提示--}}
@if(session()->has('success'))
    <div class="Huialert Huialert-success"><i class="Hui-iconfont">&#xe6a6;</i>
        {{ session('success') }}
    </div>
@endif
