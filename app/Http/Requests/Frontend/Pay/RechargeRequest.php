<?php

namespace App\Http\Requests\Frontend\Pay;

use App\Http\Requests\BaseFormRequest;

/**
 * Class RechargeRequest
 * @package App\Http\Requests\Frontend\Pay
 */
class RechargeRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'amount' => 'required|integer|min:1',
            'channel'=> 'required|string|exists:payment_infos,payment_sign',
            'bank_code' => 'string',
            'from'=> 'filled|string',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'amount.min' => '充值金额不能为零',
        ];
    }
}
