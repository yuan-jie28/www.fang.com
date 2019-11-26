<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentingController extends Controller
{
    // 图片上传
    public function upfile(Request $request)
    {
        $file = $request->file('carding');
        $info = $file->store('card', 'renting');

        return ['status' => 0, 'path' => '/uploads/renting/' . $info];
    }
}
