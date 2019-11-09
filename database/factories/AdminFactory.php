<?php
// 后台用户数据格式工厂
use Faker\Generator as Faker;

$factory->define(App\Models\Admin::class, function (Faker $faker) {
    return [
        // 填充到数据表中的格式
        'username' => $faker->userName,
        'truename' => $faker->name,
        'password' => bcrypt('admin'),
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'sex' => ['先生', '女士'][rand(0, 1)],
        'last_ip' => $faker->localIpv4
    ];
});
