<?php

namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;

class UserHandleBankCardListRequest extends BaseFormRequest
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
            'username' => 'string', //用户名
            'owner_name' => 'string', //银行卡户名
            'card_number' => 'string', //银行卡号
            'bank_sign' => 'string', //银行标识code
            'status' => 'integer', //0禁用 1可用
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
