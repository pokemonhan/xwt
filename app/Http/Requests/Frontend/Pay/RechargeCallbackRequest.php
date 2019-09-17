<?php

namespace App\Http\Requests\Frontend\Pay;

use App\Http\Requests\BaseFormRequest;

class RechargeCallbackRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'merchant_id' => 'required|string',
            'game_order_id'=> 'required|string',
            'status' => 'required|string',
            'money'=> 'required|numeric|regex:/^[0-9]+(.[0-9]{1,2})?$/',
            'time' => 'required|string',
            'sign'=> 'required|string',
        ];
    }
}
