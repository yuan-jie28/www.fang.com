<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EsController extends Controller
{
    // 创建索引  initIndex
    public function initIndex()
    {
        $hosts = config('es.hosts');
        // 实例化es对象
        $client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
        // 创建索引
        $params = [
            // 索引名
            'index' => 'fangs',
            'body' => [
                // 指定副本和分片
                'settings' => [
                    // 分片 后续不可修改
                    'number_of_shards' => 5,
                    // 副本后续可修改
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    // 字段
                    'properties' => [
                        'xiaoqu' => [
                            // 精确查询
                            'type' => 'keyword'
                        ],
                        'desn' => [
                            // 模糊搜索
                            'type' => 'text',
                            // 插件 中文分词插件  需要安装
                            'analyzer' => 'ik_max_word',
                            'search_analyzer' => 'ik_max_word'
                        ]
                    ]
                ]
            ]
        ];
        $response = $client->indices()->create($params);
        dump($response);
    }
}
