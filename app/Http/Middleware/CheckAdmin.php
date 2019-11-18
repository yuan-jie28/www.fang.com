<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;

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

        // 判断是否登录
        if (!auth()->check()) {
            // 用户没有登录则直接跳转到登录页面
            return redirect(route('admin.login'))->withErrors(['errors' => '请您先登录']);
        }

        // 登录成功后得到当前登录用户模型
        $userModel = auth()->user();

        // 只有登录成功后才进行获取用户的角色
        // 使用模型关联  根据角色ID来查询角色
        #$roleModel = auth()->user()->role()->first();
        // 简写
        $roleModel = $userModel->role;

        // 使用角色与权限的多对多关联模型获取对应的权限
        $auths = $roleModel->nodes()->pluck('route_name','id')->toArray();
        // 真正的权限
        // array_filter的作用是过滤空数据
        $authList = array_filter($auths);
        // 不需要验证的权限
        $allowList = [
            // 登录后台都有的权限
            'admin.index',
            'admin.logout',
            'admin.welcome'
        ];
        $authList = array_merge($authList,$allowList);

        // 也可以写到session中
        # session(['auths' => $authList]);
        // 把权限写到request对象中
        $request->auths = $authList;

        // 获取当前路由的别名
        $currentRouteName = $request->route()->getName();

        // 获取当前用户名
        $currentUserName = auth()->user()->username;

        // 保存当前用户名
        $request->username = $currentUserName;

        // 权限判断
        if(!in_array($currentRouteName,$authList) && $currentUserName != 'admin'){
            exit('你没有权限');
        }
        return $next($request);
    }
}
