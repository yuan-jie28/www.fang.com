<?php
// 后台路由   其中as表分组中统一指定路由别名前缀
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // 后台登录显示
    Route::get('login', 'LoginController@index')->name('login');
    // 后台登录处理
    Route::post('login','LoginController@login')->name('login');
});
