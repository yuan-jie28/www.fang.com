<?php
return [
    // 小程序官方后台获取
    'appid' => 'wxe0723ab44fede285',
    'secret' => '9fc82276bde2ea2079f76511f74e0995',
    // 小程序登录url地址 $s是占位符
    'wxloginUrl' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code'
];
