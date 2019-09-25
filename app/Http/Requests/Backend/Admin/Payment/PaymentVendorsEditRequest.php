<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentVendorsEditRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentVendorsEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_payment_vendors,id',//ID
            'payment_vendor_name' => 'required|string',//支付方式厂商名称
            'payment_vendor_sign' => 'required|string',//支付方式厂商标记
            'whitelist_ips' => 'required|string',//ip白名单
        ];
    }
}
