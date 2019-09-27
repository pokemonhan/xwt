<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

/**
 * Class FrontendAuthRegisterRequest
 * @package App\Http\Requests\Frontend
 */
class FrontendAuthRegisterRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
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
            'username' => 'required|alpha_dash|unique:frontend_users',
            'password' => ['required', 'string', 'between:6,16', 'regex:/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/'],
            're_password' => 'string',
            'keyword' => 'alpha_num',
            'register_type' => 'integer',
            'prize_group' => 'integer',//奖金组
            'type' => 'integer|in:2,3',//用户类型  2代理  3会员
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'password.regex' => '密码必须是字母+数字组合，不能有特殊字符',
            'password.between' => '密码长度6--16位之间',
        ];
    }
}
