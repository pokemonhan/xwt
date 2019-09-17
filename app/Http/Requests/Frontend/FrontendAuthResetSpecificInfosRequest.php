<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAuthResetSpecificInfosRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nickname' => 'nullable|string', //称昵
            'realname' => 'nullable|string', //真实姓名
            'mobile' => ['nullable', 'regex:/^0?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/'], //手机号码
            'email' => 'nullable|email', //邮箱
            'zip_code' => ['nullable', 'regex:/^\d{6}$/'], //邮编
            'address' => 'nullable|string', //地址
        ];
    }

    /*public function messages()
{
return [
'lottery_sign.required' => 'lottery_sign is required!',
'trace_issues.required' => 'trace_issues is required!',
'balls.required' => 'balls is required!'
];
}*/
}
