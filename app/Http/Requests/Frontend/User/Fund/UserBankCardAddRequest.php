<?php

namespace App\Http\Requests\Frontend\User\Fund;

use App\Http\Requests\BaseFormRequest;

class UserBankCardAddRequest extends BaseFormRequest
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
            'owner_name' => 'required|string', //姓名
            'bank_sign' => 'required|alpha', //银行code
            'bank_name' => 'required|string', //银行名称
            'card_number' => ['required', 'regex:/^(\d{15}|\d{16}|\d{19})$/'], //银行卡号
            'province_id' => 'required|exists:users_regions,id', //省份id
            'city_id' => 'required|exists:users_regions,id', //城市id
            'branch' => 'required|string', //支行
            'fund_password'=>'string',//资金密码
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
