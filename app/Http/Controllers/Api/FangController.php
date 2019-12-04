<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FangGroupResourceCollection;
use App\Http\Resources\FangResourceCollection;
use App\Models\Fang;
use App\Models\Fangattr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FangController extends Controller
{
    // 推荐房源
    public function recommend(Request $request)
    {
        $data = Fang::where('is_recommend', '1')->orderBy('updated_at', 'desc')->limit(5)->get(['id', 'fang_name', 'fang_pic']);
        return ['status' => 0, 'msg' => '获取成功', 'data' => $data];
    }

    // 租房小组
    public function group(Request $request)
    {
        // 字段名称
        $where['field_name'] = 'fang_group';
        // 上级id号
        $pid = Fangattr::where($where)->value('id');

        // 根据pid来返回对应的实际数组
        $data = Fangattr::where('pid', $pid)->orderBy('updated_at', 'desc')->limit(4)->get(['id', 'name', 'icon']);

        // 实例化一个集合资源对象，把模型集合对象以参数的形式传过去
        return ['status' => 0, 'msg' => '成功', 'data' => new FangGroupResourceCollection($data)];
    }

    // 房源列表
    public function fanglist(Request $request)
    {
        if (!empty($request->get('kw'))) {
            //调用一下es搜索
            return $this->search($request);
        }

        $data = Fang::orderBy('id', 'asc')->paginate(env('PAGESIZE'));
        return ['status' => 0, 'msg' => '成功', 'data' => new FangResourceCollection($data)];
    }

    // 房源详情
    public function detail(Request $request)
    {

        // 房源
        $data = Fang::with('fangowner:id,name,phone')->where('id', $request->get('id'))->first();

        $data['fang_config'] = explode(',', $data['fang_config']);
        $data['fang_config'] = Fangattr::whereIn('id', $data['fang_config'])->pluck('name');
        $data['fang_direction'] = Fangattr::where('id', $data['fang_direction'])->value('name');
        $data['fang_shi'] = Fangattr::where('id', $data['fang_shi'])->value('name');
        $data['fang_ting'] = Fangattr::where('id', $data['fang_ting'])->value('name');
        $data['fang_wei'] = Fangattr::where('id', $data['fang_wei'])->value('name');
        $data['fang_rent_money'] = Fangattr::where('id', $data['fang_rent_money'])->value('name');
        $data['fang_rent_class'] = Fangattr::where('id', $data['fang_rent_class'])->value('name');
        $data['fang_area'] = Fangattr::where('id', $data['fang_area'])->value('name');
        $data['fang_rent_range'] = Fangattr::where('id', $data['fang_rent_range'])->value('name');
        $data['fang_rent_type'] = Fangattr::where('id', $data['fang_rent_type'])->value('name');
        $data['fang_group'] = Fangattr::where('id', $data['fang_group'])->value('name');

        return ['status' => 0, 'msg' => '成功', 'data' => $data];
    }

    // 房源属性列表
    public function fangAttr(Request $request)
    {
        // 房源属性
        $attrData = Fangattr::all()->toArray();
        // 以字段名为下标创建多层数组
        $attrData = subTree2($attrData);

        return ['status' => 0, 'msg' => '成功', 'data' => $attrData];
    }

    // es搜索
    public function search(Request $request)
    {
        // 关键词的获取
        $kw = $request->get('kw');

        if (empty($kw)) {
            // kw关键词没有数据，分页显示所有的房源
            $data = Fang::orderBy('id', 'asc')->paginate(10);
            return ['status' => 0, 'msg' => '成功', 'data' => new FangResourceCollection($data)];
        }

        // 表示kw有数据
        $client = \Elasticsearch\ClientBuilder::create()->setHosts(config('es.hosts'))->build();
        // 写文档
        $params = [
            # 索引名称
            'index' => 'fangs',
            # 查询条件
            'body' => [
                'query' => [
                    'match' => [
                        'desn' => [
                            'query' => $kw
                        ]
                    ]
                ]
            ]
        ];
        $ret = $client->search($params);

        # 获取查询到的记录数  查询到一定大于0
        $total = $ret['hits']['total']['value'];
        if ($total == 0) {
            // 没有查询到对应的记录数据
            return ['status' => 0, 'msg' => '没有查询到数据', 'data' => []];
        }

        // 在二维数组中获取指定下标的值，并返回一维数组
        $data = array_column($ret['hits']['hits'], '_id');
        $data = Fang::whereIn('id', $data)->orderBy('id', 'asc')->paginate(10);
        return ['status' => 0, 'msg' => '成功', 'data' => new FangResourceCollection($data)];
    }
}
