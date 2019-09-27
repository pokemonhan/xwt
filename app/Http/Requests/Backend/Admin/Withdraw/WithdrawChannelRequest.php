<?php

namespace App\Http\Requests\Backend\Admin\Withdraw;

use App\Http\Requests\BaseFormRequest;

/**
 * Class WithdrawStatusRequest
 * @package App\Http\Requests\Backend\Admin\Withdraw
 */
class WithdrawChannelRequest extends BaseFormRequest
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
            'id' => 'required|integer|exists:users_withdraw_histories', // 提现记录的id
        ];
    }
}
