<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use QL\Ext\CurlMulti;
use QL\QueryList;

class spidercnt extends Command
{
    // 日后artisan执行的命令   me:cnt  是要执行的命令名称
    protected $signature = 'me:cnt';

    // 命令的解释
    protected $description = 'Command description';

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
        $data = \DB::table('articles')->where('is_sipder',0)->get(['id','cnt_url'])->toArray();
        $ql = QueryList::getInstance();
        $ql->use(CurlMulti::class,'curlMulti');

        $ql->curlMulti(
          array_column($data,'cnt_url')
        )->success(function (QueryList $ql,CurlMulti $curl,$r){
            // 根据url地址来获取对应修改
            $cnt_url = $r['info']['url'];
            $data = $ql->rules([
                'body' => ['.article-detail','html'],
            ])->query()->getData();
            // 查找到的内容
            $body = $data[0]['body'] ?? '';
            \DB::table('articles')->where('cnt_url',$cnt_url)->update([
               'body' => $body,
               'is_sipder' => 1,
            ]);
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
                CURLOPT_TIMEOUT => 50,
                CURLOPT_CONNECTTIMEOUT => 1,
                CURLOPT_RETURNTRANSFER => true
            ],
            // 缓存选项很容易被理解，缓存使用url来识别。如果使用缓存类库不会访问网络而是直接返回缓存。
            //'cache' => ['enable' => false, 'compress' => false, 'dir' => null, 'expire' =>86400, 'verifyPost' => false]
        ]);
    }
}
