<?php

namespace App\Http\Middleware;

use Closure;

class CheckApi
{
    // 安全验证
    public function handle($request, Closure $next)
    {
        // 获取用户接口验证相关信息
        $username = $request->header('username');
        $password = $request->header('password');
        $sign = $request->header('sign');
        $timstamp = $request->header('timstamp');

        // 根据账号和密码进行登录
        $bool = auth()->guard('api')->attempt(['username' => $username, 'password' => $password]);
        // 只有登录成功才能进行
        if (!$bool) {
            return response()->json(['status' => 100, 'msg' => '登录验证有异常！', 'data' => []], 401);
        }
        $token = auth()->guard('api')->user()->token;

        // 验签
        $signate = md5($username.$token.$password.$timstamp);
//        echo $signate;

        if($signate != $sign){
            return response()->json(['status' => 100, 'msg' => '登录验证有异常', 'data' => []], 401);
        }
        return $next($request);
    }
}
