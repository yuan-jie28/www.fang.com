<?php

use Illuminate\Database\Seeder;
// 后台用户模型
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 先清空数据表中原先数据
        Admin::truncate();

        // 调用factory数据工厂，生成数据 生成10条数据
        factory(Admin::class, 10)->create();

        // 修改 id=1的记录为  用户名为admin
        Admin::where('id', 1)->update(['username' => 'admin']);
    }
}
