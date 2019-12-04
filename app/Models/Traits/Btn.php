<?php
// 后台按钮组

namespace App\Models\Traits;

trait Btn {

    private function checkAuth(string $routeName) {
        # 在中间件中得到当前角色有持有的权限列表     ?? [] 解决前后端共用异常问题
        $auths = request()->auths ?? [];
        // 权限判断
        if (!in_array($routeName, $auths) && request()->username != 'admin') {
            return false;
        }
        return true;
    }

    // 修改
    public function editBtn(string $routeName) {
        if ($this->checkAuth($routeName)) {
            $arr['start'] = request()->get('start') ?? 0;
            // 字段在表格的索引
            $arr['field'] = request()->get('order')[0]['column'];
            // 排序类型
            $arr['order'] = request()->get('order')[0]['dir'];
            $params = http_build_query($arr);

            // 生成url地址
            $url = route($routeName,$this);
            if (stristr($url, '?' )) {
                $url = $url . '&' . $params;
            } else{
                $url = $url . '?' . $params;
            }
            return '<a href="' . $url . '" class="btn btn-secondary-outline radius">修改</a>';
        }
        return '';
    }

    // 删除
    public function delBtn(string $routeName) {
        if ($this->checkAuth($routeName)) {
            return '<a href="' . route($routeName, $this) . '" class="btn btn-danger-outline radius deluser">删除</a>';
        }
        return '';
    }

    // 查看 根据ID来显示的按钮
    public function showBtn(string $routeName) {
        if ($this->checkAuth($routeName)) {
            return '<a href="' . route($routeName, $this) . '" class="btn btn-success-outline radius showBtn">查看</a>';
        }
        return '';
    }

}
