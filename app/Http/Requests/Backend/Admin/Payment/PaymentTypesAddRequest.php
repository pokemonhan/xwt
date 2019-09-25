<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentTypesAddRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentTypesAddRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.git
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
            'payment_type_name' => 'required|string|unique:backend_payment_types,payment_type_name',//支付方式种类名称
            'payment_type_sign' => 'required|string|unique:backend_payment_types,payment_type_sign',//支付方式种类标记
            'is_bank' => 'required|in:0,1',//是否是银行 0 不是 1 是
            'payment_ico' => 'required|image|mimes:jpeg,png,jpg,ico',//支付方式图标
        ];
    }
}
