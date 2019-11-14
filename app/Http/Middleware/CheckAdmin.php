<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $params 是第3个参数  它是获取中间件用：传过来的参数，此参数只有在中间件有传参时才写
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 中间件
        // echo '<h3>我是中间件</h3>';

        if (!auth()->check()) {
            // 用户没有登录则直接跳转到登录页面
            return redirect(route('admin.login'))->withErrors(['errors' => '请您先登录']);
        }

        return $next($request);
    }
}
