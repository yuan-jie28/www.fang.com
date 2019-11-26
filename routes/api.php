<?php

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// 登录
// 请求地址 api/login
/*Route::get('login', function () {
    // 获取请求头信息数据
    $username = request()->header('username');
    $password = request()->header('password');
    $timstamp = request()->header('timstamp');
    $signate = request()->header('sign');
    $userData = ['username' => $username, 'password' => $password];
    // auth登录
    $bool = auth()->guard('api')->attempt($userData);

    // 异常情况统一到一个地方处理  异常的地方
    if (!$bool) {
        // 登录不成功
        throw new \App\Exceptions\LoginException(config('exception')[1], 1);
    }
    // 登录成功，进行签名比较
    $token = auth()->guard('api')->user()->token;
    // 签名计算
    $sign = $username . $token . $timstamp . $password;
    $sign = md5($sign);
    if ($sign != $signate) {
        // 处理
        throw new \App\Exceptions\LoginException(config('exception'), 2);
    }
    return ['status' => 0, 'msg' => '登录成功'];

});*/

// 接口
// restful规范中url中最好有版本  api/v1/ 有此前缀
Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    // 实现小程序的登录
    Route::post('wxlogin','WxloginController@login');

    // 小程序授权
    Route::post('userinfo','WxloginController@userinfo');

    // 图片上传
    Route::post('upfile','RentingController@upfile');
});
