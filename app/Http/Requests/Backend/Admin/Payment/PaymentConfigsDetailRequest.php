<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentConfigsDetailRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentConfigsDetailRequest extends BaseFormRequest
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
        ];
    }
}
