<?php
// 小程序登录
namespace App\Http\Controllers\Api;

use App\Exceptions\MyValidateException;
use App\Models\Renting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxloginController extends Controller
{
    // 登录
    public function login(Request $request)
    {
        $code = $request->get('code');
        $appid = config('wx.appid');
        $secret = config('wx.secret');
        $url = sprintf(config('wx.wxloginUrl'), $appid, $secret, $code);

        // 发起一个GET请求  verify不检查ssl证书
        $client = new Client(['timeout' => 5, 'verify' => false]);
        $response = $client->get($url);
        // 得到请求响应值
        $json = (string)$response->getBody();
        // 转为数组
        $arr = json_decode($json,true);
        $openid = $arr['openid'] ?? 'none';

        if ($openid != 'none'){
            // 根据OPENID来进行数据是否存在的判断
            $info = Renting::where('openid',$openid)->value('openid');
            if(!$info){
                // 数据不存在,则添加
                Renting::create(['openid'=>$openid]);
            }
        }
        return ['openid' => $openid];
    }

    // 授权
    public function userinfo(Request $request)
    {
        // 表单验证
        try{
            $data = $this->validate($request,[
                'openid'    => 'required',
                'nickname'  => 'required',
                'sex'       => 'required',
                'avatar'    => 'required'
            ]);
        }catch (\Exception $exception){
            throw new MyValidateException('验证不通过',3);
        }
        // 如果存在得到当前模型对象
        $model = Renting::where('openid',$data['openid'])->first();

        if (!$model){
            // 此用此用户不存在,则添加
            Renting::create($data);
        }else{
            // 用户存在，则修改
            $model->update($data);
        }
        return ['status' => 0, 'msg' => '成功'];
    }
}
