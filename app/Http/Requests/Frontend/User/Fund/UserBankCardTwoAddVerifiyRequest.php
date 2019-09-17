<?php

namespace App\Http\Requests\Frontend\User\Fund;

use App\Http\Requests\BaseFormRequest;

class UserBankCardTwoAddVerifiyRequest extends BaseFormRequest
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
            'fund_password'=>'required|string',//资金密码
        ];
    }
}
