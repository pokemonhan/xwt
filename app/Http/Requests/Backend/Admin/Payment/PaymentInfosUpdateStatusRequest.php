<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * 编辑支付方式详情表状态
 * Class PaymentInfosUpdateStatusRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentInfosUpdateStatusRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:payment_infos,id',//表backend_payment_configs中的id
            'status' => 'in:0,1',//状态 1 启用 0 停用
        ];
    }
}
