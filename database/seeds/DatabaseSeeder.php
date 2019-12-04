<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // 生成后台用户数据
        $this->call(AdminSeeder::class);

        // $this->call(CateSeeder::class);

        // 文章
//        $this->call(ArticleSeeder::class);
    }
}
