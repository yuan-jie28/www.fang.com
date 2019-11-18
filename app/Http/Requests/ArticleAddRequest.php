<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'body'  => 'required'
        ];
    }

    /**
     *  验证消息
     */
    public function messages()
    {
        return [
          'title.required'  => '标题不能为空',
          'body.required'   => '内容不能为空'
        ];
    }
}
