<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentVendorsAddRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentVendorsAddRequest extends BaseFormRequest
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
            'payment_vendor_name' => 'required|string|unique:backend_payment_vendors,payment_vendor_name',//支付方式厂商名称
            'payment_vendor_sign' => 'required|string|unique:backend_payment_vendors,payment_vendor_sign',//支付方式厂商标记
            'whitelist_ips' => 'required|string',//IP白名单
        ];
    }
}
