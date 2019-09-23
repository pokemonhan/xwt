<?php

namespace App\Http\Requests\Frontend\UserAgentCenter;

use App\Http\Requests\BaseFormRequest;

/**
 * 转账给下级
 */
class UserAgentCenterTransferToChildRequest extends BaseFormRequest
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
            'user_id' => 'required|integer|exists:frontend_users,id', //下级的用户id
            'amount' => 'required|numeric', //转账金额
            'fund_password' => ['required', 'string', 'between:6,18', 'regex:/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/'], //资金密码
            'bank_card_id' => 'required|integer', // 银行卡id
            'bank_card_number' => ['required', 'regex:/^(\d{15}|\d{16}|\d{19})$/'], //银行卡号
        ];
    }

    /**
     * 自定义返回信息
     * @return array
     */
    public function messages()
    {
        return [
            'fund_password.between' => '资金密码长度必须是6到18位',
            'fund_password.regex' => '资金密码必须是字母+数字组合，不能有特殊字符',
            'bank_card_number.regex' => '银行卡号格式不正确',
        ];
    }
}
