<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use QL\Ext\CurlMulti;
use QL\QueryList;

class Sipder extends Command
{
    // 日后artisan执行的命令   me:sipder  是要执行的命令名称
    protected $signature = 'me:sipder';

    // 命令的解释
    protected $description = '采集数据命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // 敲命令  真正执行代码的地方
    public function handle()
    {
        $ql = QueryList::getInstance();
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
            foreach ($data as $item){
                $item['cid'] = 2;
                $item['body'] = '';
                Article::create($item);
            }
            echo "ok\n";
        })->error(function ($errorInfo,CurlMulti $curl){
            echo "Current url:{$errorInfo['info']['url']} \r\n";
            echo $errorInfo['error'];
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
        ]);
    }
}
