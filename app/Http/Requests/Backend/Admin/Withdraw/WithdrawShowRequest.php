<?php

namespace App\Http\Requests\Backend\Admin\Withdraw;

use App\Http\Requests\BaseFormRequest;

/**
 * Class WithdrawShowRequest
 * @package App\Http\Requests\Backend\Admin\Withdraw
 */
class WithdrawShowRequest extends BaseFormRequest
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
            'start_time' => 'date_format:Y-m-d H:i:s', //开始时间
            'end_time' => 'date_format:Y-m-d H:i:s' , //结束时间
        ];
    }
}
