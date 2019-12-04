<?php
// 后台首页
namespace App\Http\Controllers\Admin;

use App\Models\Fang;
use App\Models\Node;
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
        // 获取闪存后，再存到闪存中
        session()->flash('success', session('success'));

        // 得到当前的登录用户
        $userModel = auth()->user();
        // 用户对应的角色关联关系  属于
        $roleModel = $userModel->role;
        // 得到有菜单权限的权限
        if ($userModel->username != 'admin') {
            // 普通用户
            $nodeData = $roleModel->nodes()->where('is_menu', '1')->get(['id', 'pid', 'name', 'route_name'])->toArray();
        } else {
            // 超级管理员
            $nodeData = Node::where('is_menu', '1')->get(['id', 'pid', 'name', 'route_name'])->toArray();
        }
        // 调用递归函数，进行多层数组嵌套
        $menuData = subTree($nodeData);

        return view('admin.index.index', compact('menuData'));
    }

    // 后台欢迎页面
    public function welcome()
    {
               # 已组
        $count1 = Fang::where('fang_status', 1)->count();
        # 待租
        $count2 = Fang::where('fang_status', 0)->count();

        // 拼接图形所需的数据
        $legend = "'已组','待租'";
        $data = [
            ['value' => $count1, 'name' => '已组'],
            ['value' => $count2, 'name' => '待租'],
        ];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return view('admin.index.welcome',compact('legend','data'));
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
        return redirect(route('admin.login'))->with('success', '退出成功');
    }
}
