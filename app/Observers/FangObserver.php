<?php

namespace App\Observers;

use App\Models\Fang;

// 网络请求类
use GuzzleHttp\Client;

class FangObserver
{
    // 添加之前得到地址转化为经纬度
    public function creating(Fang $fang)
    {
        // 把地址转为经纬度  请求地址
        $url = sprintf(config('geo.url'),request()->get('fang_addr'));
        // 引入Guzzle类发起GET请求  timeout是超时时间  verify是https协议请求   true是https  false是http
        $client = new Client(['timeout' => 5,'verify' => false]);
        $response = $client->get($url);
        // 获取请求的主体
        $json = (string)$response->getBody();
        $arr = json_decode($json,true)['geocodes'];

        $longitude = $latitude = 0;
        if(count($arr)>0){
            $location = $arr[0]['location'];
            // 把地址转为经纬度  解构赋值
            [$longitude,$latitude] = explode(',',$location);
        }
        $fang->longitude = $longitude;
        $fang->latitude = $latitude;

        // 处理配套设施转为字符串以逗号隔开
        $fang->fang_config = implode(',',request()->get('fang_config'));
    }
}
