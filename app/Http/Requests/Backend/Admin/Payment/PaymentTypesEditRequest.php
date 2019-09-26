<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentTypesEditRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentTypesEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_payment_types,id',//ID
            'payment_type_name' => 'string',//支付方式种类名称
            'payment_type_sign' => 'string',//支付方式种类标记
            'is_bank' => 'required|in:0,1',//是否是银行 0 不是 1 是
            'payment_ico' => 'image|mimes:jpeg,png,jpg,ico',//支付方式图标
        ];
    }
}
