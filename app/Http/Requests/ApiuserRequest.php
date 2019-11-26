<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiuserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // 规则
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'token'    => 'required',
        ];
    }

    // 消息
    public function messages()
    {
        return [
          'token.required' => '接口token不能为空'
        ];
    }
}
