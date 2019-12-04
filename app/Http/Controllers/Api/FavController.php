<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\MyValidateException;
use App\Http\Resources\FavResourceCollection;
use App\Models\Fav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavController extends Controller
{
    // openid用户是否收藏
    public function isfav(Request $request)
    {
        // 表单验证
        try {
            $data = $this->validate($request, [
                'openid' => 'required',
                'fang_id' => 'required|numeric'
            ]);
        } catch (\Exception $exception) {
            throw new MyValidateException('验证不通过', 3);
        }

        // 验证通过，进行数据唯一行判断，openid+房源id在此表中的数据唯一
        $model = Fav::where($data)->first();
        if ($model) {
            return ['status' => 0, 'msg' => '取消收藏', 'data' => 1];
        }
        return ['status' => 0, 'msg' => '添加收藏', 'data' => 0];
    }

    // 添加收藏或取消收藏
    public function fav(Request $request)
    {
        // 表单验证
        try {
            $data = $this->validate($request, [
                'openid' => 'required',
                'fang_id' => 'required|numeric',
                // 添加收藏还是取消收藏的标识位
                'add' => 'required|numeric'
            ]);
        } catch (\Exception $exception) {
            throw new MyValidateException('验证不通过', 3);
        }

        $add = $data['add'];
        unset($data['add']);

        // 验证通过，进行数据唯一行判断，openid+房源id在此表中的数据唯一
        $model = Fav::where($data)->first();
        if ($add > 0) {
            // 判断是取消收藏还是添加收藏  0 是取消  大于0的为添加
            // 添加收藏
            if (!$model) {
                // 数据不存在则添加，存在什么操作也不执行
                Fav::create($data);
            }
            $msg = '添加收藏';
        } else {
            // 取消收藏   数据存在才操作，不存在什么操作也不执行
            if ($model) {
                // 永久删除
                $model->forceDelete();
            }
            $msg = '取消收藏成功';
        }
        return ['status' => 0, 'msg' => $msg];
    }

    // 收藏房源列表
    public function list(Request $request)
    {
        // 表单验证
        try {
            $data = $this->validate($request, [
                'openid' => 'required'
            ]);
        } catch (\Exception $exception) {
            throw new MyValidateException('验证不通过', 3);
        }
        $data = Fav::where('openid', $data['openid'])->orderBy('updated_at', 'asc')->paginate(10);
        return ['status' => 0, 'msg' => '成功', 'data' => new FavResourceCollection($data)];
    }
}
