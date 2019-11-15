<?php
// 后台路由   其中as表分组中统一指定路由别名前缀
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // 后台登录显示
    Route::get('login', 'LoginController@index')->name('login');
    // 后台登录处理
    Route::post('login', 'LoginController@login')->name('login');


    //------------------------------------------------
    // 这是路由中间件的第3种方法，在路由分组中统一绑定一个中间件
    Route::group(['middleware' => ['checkadmin']], function () {

        // 后台首页
        Route::get('index', 'IndexController@index')->name('index');
        // 后台欢迎页面
        Route::get('welcome', 'IndexController@welcome')->name('welcome');
        // 这是路由中间件的第2种定义方法
        // Route::get('welcome','IndexController@welcome')->name('welcome')->middleware('checkadmin');
        // 退出登录
        Route::get('logout', 'IndexController@logout')->name('logout');

        //------------------------------------------------
        // 用户管理
        // 用户列表
        Route::get('user/index','AdminController@index')->name('user.index');

        // 添加用户显示
        Route::get('user/create','AdminController@create')->name('user.create');
        // 添加用户处理
        Route::post('user/create','AdminController@store')->name('user.store');

        // 修改用户显示  修改时一定要有参数，才能针对指定用户来修改
        Route::get('user/edit/{id}','AdminController@edit')->name('user.edit');
        // 修改用户处理
        Route::put('user/edit/{id}','AdminController@update')->name('user.update');

        // 删除用户
        Route::delete('user/destroy/{id}','AdminController@destroy')->name('user.destroy');
        // 全选删除
        Route::delete('user/delall','AdminController@delall')->name('user.delall');

        // 恢复
        Route::get('user/restore','AdminController@restore')->name('user.restore');

        //--------------------------------------------------------------
        // 角色管理
        // 角色列表
        // 单个的定义路由，太麻烦，laravel提供资源路由，定义一个提供7种路由，满足了我们的增删改查
        // 定义资源路由
        Route::resource('role','RoleController');

        //----------------------------------------
        // 权限管理
        Route::resource('node','NodeController');

    });


});
