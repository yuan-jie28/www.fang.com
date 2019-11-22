<?php

namespace App\Http\Requests;

use Validator;
use Illuminate\Foundation\Http\FormRequest;

class FangOwnerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // 调用自定义验证规则
        $this->advRules();

        return [
            'phone' => 'required|checkPhone',
            'card' => 'required|checkCard'
        ];
    }

    public function messages()
    {
        return [
            'phone.check_phone' => '手机号码不合法',
            'card.check_card' => '身份证号码不合法'
        ];
    }

    private function advRules()
    {
        // 手机号码
        Validator::extend('checkPhone', function ($attribute, $value, $parameters, $validator) {
            $reg = '/^1[3-9]\d{9}$/';
            return preg_match($reg, $value);
        });
        // 自定义身份证
        Validator::extend('checkCard', function ($attribute, $value, $parameters, $validator) {
            $card = trim($value);
            return preg_match('/\d{17}[\dx]$/i', $card) && (strlen($card) == 18);
        });
    }
}
