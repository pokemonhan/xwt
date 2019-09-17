<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAuthRegisterRequest extends BaseFormRequest
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
            'username' => 'required|alpha_dash|unique:frontend_users',
            'password' => 'required|string',
            're_password' => 'string',
            'keyword' => 'alpha_num',
            'register_type' => 'integer',
            'prize_group' => 'integer',//奖金组
        ];
    }
}
