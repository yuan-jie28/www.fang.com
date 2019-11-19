<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cate;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleAddRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 客户端分页
        // 获取所有的文章数据
        $data = Article::all();

        return view('admin.article.index',compact('data'));
    }

    // 添加文章显示
    public function create()
    {
        // 读取分类信息
        $cateData = Cate::all()->toArray();
        $cateData = treeLevel($cateData);

        return view('admin.article.create', compact('cateData'));
    }

    // 文件上传
    public function upfile(Request $request)
    {
//        return ['status' => 0];
        // 获取上传表单文件域名称对应的对象
        $file = $request->file('file');
        // 上传
        // 参1   在节点名称指定的目录下面创建一个新的以此名称的目录，可以不写为空，不创建
        // 参2   在config中filesystems.php文件中配置的节点名称
        // 返回上传成功的相对路径
        $url = $file->store('', 'article');

        return ['status' => 0, 'url' => '/uploads/articles/' . $url];
    }

    // 添加处理
    public function store(ArticleAddRequest $request)
    {
        $data = $request->except(['_token','file']);
        // 入库
        Article::create($data);
        return redirect(route('admin.article.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
