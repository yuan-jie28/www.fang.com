<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // 后台登录显示
    public function index(){
        return view('admin.login.index');
    }

    // 登录处理
    public function login(Request $request){
        // 表单验证  laravel5.5验证通过后返回验证字段值
        $data = $this->validate($request,[
            'username' => 'required',
            'password' => 'required',
        ]);

        // auth登录
        //$bool = auth()->guard('web')->attempt($data);
        // 简写 因为默认为web  如果不是默认的则一定要写guard指定
        $bool = auth()->attempt($data);

        dump($bool);
        //dump($request->all());
        // 得到用户的信息
        dump(auth()->user());
    }
}
