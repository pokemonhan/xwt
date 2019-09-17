<?php

namespace App\Http\Requests\Frontend\User\Fund;

use App\Http\Requests\BaseFormRequest;

class UserRechargeRequest extends BaseFormRequest
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
            'amount'    => 'required|string', // 充值金额
            'channel'   => 'required|alpha', // 渠道
        ];
    }
}
