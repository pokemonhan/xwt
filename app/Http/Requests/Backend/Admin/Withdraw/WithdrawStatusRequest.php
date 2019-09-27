<?php

namespace App\Http\Requests\Backend\Admin\Withdraw;

use App\Http\Requests\BaseFormRequest;
use App\Models\User\UsersWithdrawHistorie;

/**
 * Class WithdrawStatusRequest
 * @package App\Http\Requests\Backend\Admin\Withdraw
 */
class WithdrawStatusRequest extends BaseFormRequest
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
            'status' => 'required|integer', //要改变的状态
            'remark' => 'string|required_if:status,'.UsersWithdrawHistorie::STATUS_AUDIT_FAILURE, //操作理由
            'channel' => 'string|exists:payment_infos,payment_sign|required_if:status,'.UsersWithdrawHistorie::STATUS_AUDIT_SUCCESS, //通道id
        ];
    }
}
