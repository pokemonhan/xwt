<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAuthResetFundPasswordRequest extends BaseFormRequest
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
            // 'id' => 'required|numeric|exists:frontend_users',
            // 'username' => 'required|exists:frontend_users',
            'old_password' => 'required|string',
            'password' => ['required', 'string', 'between:6,18', 'regex:/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/', 'confirmed', 'different:old_password'],
            'password_confirmation' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => '资金密码必须是字母+数字的组合，不能有特殊字符',
        ];
    }
}
