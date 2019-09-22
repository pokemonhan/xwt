<?php

namespace App\Http\Requests\Frontend\UserAgentCenter;

use App\Http\Requests\BaseFormRequest;

/**
 * 转账给下级
 */
class UserAgentCenterTransferToChildRequest extends BaseFormRequest
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
            'user_id' => 'required|integer|exists:frontend_users,id',
            'amount' => 'required|numeric',
        ];
    }

    /*public function messages()
    {

    }*/
}
