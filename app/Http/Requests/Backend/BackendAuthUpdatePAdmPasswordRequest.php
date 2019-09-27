<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseFormRequest;

/**
 * Class BackendAuthUpdatePAdmPasswordRequest
 * @package App\Http\Requests\Backend
 */
class BackendAuthUpdatePAdmPasswordRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_users',
            'name' => 'required|string',
            'password' => ['required', 'string', 'between:6,16', 'regex:/^(?=.*[a-zA-Z]+)(?=.*[0-9]+)[a-zA-Z0-9]+$/'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'password.between' => '管理员密码必须是6---16位之间',
            'password.regex' => '管理员密码必须是字母+数字组合，不能有特殊字符',
        ];
    }
}
