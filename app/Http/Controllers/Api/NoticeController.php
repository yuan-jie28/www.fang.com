<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MyValidateException;
use App\Models\Article;
use App\Models\Notice;
use App\Models\Renting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 引入采集类
use QL\QueryList;
// 采用多线程
use QL\Ext\CurlMulti;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        // 表单验证
        try {
            $data = $this->validate($request, [
                'openid' => 'required'
            ]);
        } catch (\Exception $exception) {
            throw new MyValidateException('验证不通过', 3);
        }
        // 根据openid来获取对应的租客id
        $renting_id = Renting::where($data)->value('id');

        // 根据租客id来返回对应的看房通知
        $data = Notice::with('fangowner:id,name,phone')->where('renting_id', $renting_id)->orderBy('id', 'asc')->paginate(env('PAGESIZE'));
        return ['status' => 0, 'msg' => '成功', 'data' => $data];
    }

    // 自定义名称的全局变量
    protected static $i = 1;
    // 采集信息
    public function sipder()
    {
        // 采集标题信息
        /*$data = QueryList::get('https://news.ke.com/bj/baike/0269254.html')
            // 设置采集规则
            ->rules([
                'title'=>array('title','text'),
            ])
            ->queryData();

        dump($data);*/


        // 采集图片
        /*$data = QueryList::get('https://www.ivsky.com/tupian/ziranfengguang/')
            // 设置采集规则
            ->rules([
                // 参1 选择器  css选择器一样
                // 参2 属性名，text(标签中的文本) html(标签中的html)
                // 参3 去除的标签，一般和参2中用html  <p><img><div></div></p>   => <p><img></p>  -div
                // 参4 回调方法，一般用于采集到的数据的处理

                'src'=>array('.il_img a img ','src','',function($item){

                    // 保存图片 自定义名称
                    $filename = self::$i . '.' . pathinfo($item)['extension'];
                    // 保存到本地路径和文件名称
                    $filepath = public_path('img/' . $filename);
                    // 自定义名称自增
                    self::$i +=1;
                    // 请求图片资源
                    $client = new Client(['timout' => 5, 'verify' => false]);
                    $reponse = $client->get($item);
                    // 写入到本地
                    file_put_contents($filepath,$reponse->getBody());
                    return '/img/' . $filename;
                }),
            ])
            ->queryData();

        dump($data);*/


        // 多线程采集数据
        /*$ql = QueryList::getInstance();
        $ql->use(CurlMulti::class,'curlMulti');

        $ql->curlMulti([
            'https://news.ke.com/bj/baike/033/pg1/',
            'https://news.ke.com/bj/baike/033/pg2/',
            'https://news.ke.com/bj/baike/033/pg3/',
            'https://news.ke.com/bj/baike/033/pg4/',
            'https://news.ke.com/bj/baike/033/pg5/'
        ])->success(function (QueryList $ql,CurlMulti $curl,$r){
            $data = $ql->rules([
                'title' => ['.tit','text'],
                'desn'  => ['.text p.summary', 'text'],
                'pic'   => ['.item .img img','data-original'],
                'cnt_url' => ['.item a.img','href'],
            ])->query()->getData();
            dump($data->all());
        })->error(function ($errorInfo,CurlMulti $curl){
            echo "Current url:{$errorInfo['info']['url']} \r\n";
            dump($errorInfo['error']);
        })->start([
            // 最大并发数，这个值可以运行中动态改变。
            'maxThread' => 100,
            // 触发curl错误或用户错误之前最大重试次数，超过次数$error指定的回调会被调用。
            'maxTry' => 10,
            // 全局CURLOPT_*
            'opt' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 1,
                CURLOPT_RETURNTRANSFER => true
            ],
            // 缓存选项很容易被理解，缓存使用url来识别。如果使用缓存类库不会访问网络而是直接返回缓存。
            'cache' => ['enable' => false, 'compress' => false, 'dir' => null, 'expire' =>86400, 'verifyPost' => false]
        ]);*/
    }
}
