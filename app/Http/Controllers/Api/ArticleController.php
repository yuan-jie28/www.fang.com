<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MyValidateException;
use App\Models\Article;
use App\Models\ArticleCount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    // 文章列表
    public function index()
    {
        // 要获取的字段数据
        $fields = [
            'id',
            'title',
            'desn',
            'pic',
            'created_at'
        ];
        $data = Article::orderBy('id', 'asc')->select($fields)->paginate(env('PAGESIZE'));
        return ['status' => 0, 'msg' => '成功', 'data' => $data];
    }

    // 资讯详情  根据id来获取数据
    public function show(Article $article)
    {
        return ['status' => 0, 'msg' => '成功', 'data' => $article];
    }

    // 记录用户浏览次数
    public function history(Request $request)
    {
        // 表单验证
        try {
            $data = $this->validate($request, [
                'openid' => 'required',
                'art_id' => 'required|numeric'
            ]);
        } catch (\Exception $exception) {
            throw new MyValidateException('验证不通过', 3);
        }

        // 获取当前时间
        $data['vdt'] = date('Y-m-d');

        // 判断 openid和art_id文章id和当天日期是否存在，如果存在则修改，不存在则添加一条新记录
        // 使用了一个数组作为where条件
        $model = ArticleCount::where($data)->first();
        if (!$model) {
            // 当数据不存在
            $data['click'] = 1;
            $model = ArticleCount::create($data);
        } else {
            // 数据存在则修改  increment自增加1
            $model->increment('click');
        }

        // 返回数据  post请求，返回的http状态码为201
        return response()->json(['status' => 0, 'msg' => '记录成功', 'data' => $model->click], 201);
    }
}
