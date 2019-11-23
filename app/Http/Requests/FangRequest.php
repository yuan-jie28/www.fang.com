<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FangRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // 规则
    public function rules()
    {
        return [
            'fang_xiaoqu' => 'required',
            // 默认接受是字符窜，转为数字
            'fang_province' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'fang_province.required' => '省份必须选择',
            'fang_province.min' => '请选择省份',
        ];
    }
}
