<?php

namespace App\Http\Requests\Frontend\Pay;

use App\Http\Requests\BaseFormRequest;

/**
 * Class WithdrawRequest
 * @package App\Http\Requests\Frontend\Pay
 */
class WithdrawRequest extends BaseFormRequest
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
            'amount' => 'required|integer',
            'card_id' => 'required|integer',
            'from'=> 'filled|string',
            'fund_password'=> 'required|string',
        ];
    }

    /**
     * @return array
     */
    public function messages() :array
    {
        return [
            'fund_password.required' => '请填写资金密码',
        ];
    }
}
