<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // 后台登录显示
    public function index()
    {
//        echo bcrypt('admin');exit;
        return view('admin.login.index');
    }


    // 登录处理
    public function login(Request $request)
    {
        // 表单验证  laravel5.5验证通过后返回验证字段值
        $data = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        // auth登录
        //$bool = auth()->guard('web')->attempt($data);
        // 简写 因为默认为web  如果不是默认的则一定要写guard指定
        $bool = auth()->attempt($data);
//        dd($bool);

        /*dump($bool);
        //dump($request->all());
        // 得到用户的信息
        dump(auth()->user());*/

        // 判断用户是否登录成功
        if (!$bool) {
            // 用户没有登录成功，返回到登录页面，并利用闪存给用户提示
            // withErrors方法就是向模板中的$errors对象中添加错误信息
            return redirect(route('admin.login'))->withErrors(['error' => '登录失败']);
        }

        // 登录成功则跳转到后台首页
        return redirect(route('admin.index'));
    }
}
