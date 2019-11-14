<?php
// 后台首页
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    // 控制器构造方法
    public function __construct()
    {
        // 第1中方法  绑定路由中间件  不过不推荐使用
        // 其中checkadmin是注册路由中间件中数组下标名称
        // $this->middleware('checkadmin');
    }

    // 后台首页
    public function index()
    {
        return view('admin.index.index');
    }

    // 后台欢迎页面
    public function welcome()
    {
        return view('admin.index.welcome');
    }

    // 退出用户登录
    public function logout()
    {
        // 退出
        //auth()->guard('web')->logout();
        // 因为guard中的web是默认，所以可以省略简写
        auth()->logout();

        // 退出之后跳转到登录首页
        // with【不会】把消息添加到$errors中
        // with会写入闪存中  闪存也是session
        // 闪存在创建后，只有在第1个http请求中才能得到，后边就没有用了
        // 闪存的获取和session一样  session('success');
        return redirect(route('admin.login'))->with('success','退出成功');
    }
}
