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

//Route::get('aa',function (){
//   return 'aaa';
//})->middleware('checkapi');
// 接口
// restful规范中url中最好有版本  api/v1/ 有此前缀
Route::group(['prefix' => 'v1', 'namespace' => 'Api','middleware'=>['checkapi']], function () {
    // 实现小程序的登录
    Route::post('wxlogin','WxloginController@login');

    // 小程序授权
    Route::post('userinfo','WxloginController@userinfo');

    // 图片上传
    Route::post('upfile','RentingController@upfile');

    // 租客信息处理
    Route::put('editrenting','RentingController@editrenting');

    // 以openid来反返回用户信息
    Route::get('renting','RentingController@show');

    // 看房通知
    Route::get('notices','NoticeController@index');
    Route::get('sipder','NOticeController@sipder');

    // 记录用户浏览次数记录
    Route::post('articles/history','ArticleController@history');
    // 资讯详情
    Route::get('articles/{article}','ArticleController@show');
    // 文章列表
    Route::get('articles','ArticleController@index');

    // 房源推荐接口
    Route::get('fang/recommend','FangController@recommend');

    // 租房小组
    Route::get('fang/group','FangController@group');

    // 房源列表
    Route::get('fang/fanglist','FangController@fanglist');

    // 房源详情
    Route::get('fang/detail','FangController@detail');

    // 收藏记录
    Route::get('fang/fav','FavController@fav');
    // 是否收藏
    Route::get('fang/isfav','FavController@isfav');
    // 收藏记录表
    Route::get('fang/list','FavController@list');

    // 看房
    Route::get('fang/can',function (){
        return ['status'=>0,'msg'=>'看房'];
    });

    // 房源属性
    Route::get('fang/attr','FangController@fangAttr');

    // es模糊查询
    Route::get('fang/search','FangController@search');
});
