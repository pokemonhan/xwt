<?php

namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;

class UserHandleCreateUserRequest extends BaseFormRequest
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
        $minBetPrize = configure('min_bet_prize_group') ?? 1800;
        $maxBetPrize = configure('max_bet_prize_group') ?? 1960;
        return [
            'username' => 'required|unique:frontend_users',
            'password' => ['required', 'string', 'between:6,16','different:fund_password', 'regex:/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/'],
            'fund_password' => ['required', 'string','between:6,18','different:password', 'regex:/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/'],
            'is_tester' => 'required|numeric',
            'prize_group' => 'required|numeric|between:' . $minBetPrize . ',' . $maxBetPrize,
            'type' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
        'password.regex' => '密码必须是字母+数字组合，不能有特殊字符',
        'fund_password.regex' => '资金密码必须是字母+数字组合，不能有特殊字符',
        ];
    }
}
