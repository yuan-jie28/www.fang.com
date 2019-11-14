<?php
// 定义一个后台公共控制器来初始化分页数量
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    // 分页数
    protected $pagesize = 1;

    public function __construct()
    {
        $this->pagesize = env('PAGESIZE');
    }
}
