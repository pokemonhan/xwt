<?php

namespace App\Http\Requests\Backend\Admin\Payment;

use App\Http\Requests\BaseFormRequest;

/**
 * Class PaymentInfosSortRequest
 * @package App\Http\Requests\Backend\Admin\Payment
 */
class PaymentInfosSortRequest extends BaseFormRequest
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
            'front_sort' => 'required|numeric|gt:0',
            'rearWays_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|numeric|in:1,2',
            'front_id' => 'required|exists:payment_infos,id',
            'rearWays_id' => 'required|exists:payment_infos,id',
        ];
    }
}
