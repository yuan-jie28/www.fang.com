<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cate;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleAddRequest;

class ArticleController extends BaseController
{
    // 文章列表
     public function index(Request $request) {

        // 判端是否是Ajax请求
        if ($request->ajax()) {

            // 服务器端分页
            // 分页
            // 起始位置
            $offset = $request->get('start', 0);
            // 获取的记录总数
            $limit = $request->get('length', $this->pagesize);

            // 排序
            $order = $request->get('order')[0];

            // 排序字段数组
            $columns = $request->get('columns')[$order['column']];

            // 排序的规则
            $orderType = $order['dir'];

            // 排序字段
            $field = $columns['data'];

            // 关联关系字段的映射
            $field = $field != 'cate.cname' ? $field : 'cid';

            // 搜索
            $kw = $request->get('kw');
            $builer = Article::when($kw, function ($query) use ($kw) {
                $query->where('title', 'like', "%{$kw}%");
            });

            // 获取记录总数
            $count = $builer->count();

            // 获取所有的文章数据
            // with调用模型关联  推荐使用 with只能有一个参数，如果有多个则使用数组，一对多的时候也可以使用条件
            $data = $builer->with('cate')->orderBy($field, $orderType)->offset($offset)->limit($limit)->get();
            // 返回指定格式的json数据
            return [
                // draw:客户端调用服务器端次数标识
                'draw' => $request->get('draw'),
                // recordsTotal: 获取数据记录总条数
                'recordsTotal' => $count,
                // recordsFiltered: 数据过滤后的总数量
                'recordsFiltered' => $count,
                // data: 获得的具体数据
                'data' => $data
            ];
        }


        return view('admin.article.index');
     }

    // 添加文章显示
    public function create()
    {
        // 读取分类信息
        $cateData = Cate::all()->toArray();
        $cateData = treeLevel($cateData);

        return view('admin.article.create', compact('cateData'));
    }

    // 删除图片
    public function delfile(Request $request)
    {
        $id = $request->get('id');
        // 要删除的图片的相对地址
        $src = $request->get('src');
        // 绝对地址
        $filepath = public_path($src);
        if (is_file($filepath)) {
            // 文件若存在，则删除  注意Linux文件的权限问题
            unlink($filepath);
        }
        return ['status' => 0, 'msg' => '删除成功'];
    }

    // 添加处理
    public function store(ArticleAddRequest $request)
    {
        $data = $request->except(['_token', 'file']);
        // 入库
        Article::create($data);
        return redirect(route('admin.article.index'));
    }

    // 修改显示
    public function edit(Request $request, Article $article)
    {
        // 获取url参数
        $url_query = $request->all();

        // 读取分类信息
        $cateData = Cate::all()->toArray();
        $cateData = treeLevel($cateData);
        return view('admin.article.edit', compact('cateData', 'article', 'url_query'));
    }

    // 修改处理
    public function update(ArticleAddRequest $request, Article $article)
    {
       // 传过来的url参数
       $url = $request->get('url');
       $article->update($request->except(['file','_method','_token','url']));
       $url = route('admin.article.index') . '?' . http_build_query($url);
       return redirect($url);
    }

    // 删除操作
    public function destroy(Article $article)
    {
        $article->delete();
        return ['status' => 0, 'msg' => '删除成功'];
    }
}
