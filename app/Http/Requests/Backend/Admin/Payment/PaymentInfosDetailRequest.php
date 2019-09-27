<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentInfosDetailRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentInfosDetailRequest extends BaseFormRequest
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
            'front_name' => 'string|exists:payment_infos,front_name',//前台名称
        ];
    }
}
