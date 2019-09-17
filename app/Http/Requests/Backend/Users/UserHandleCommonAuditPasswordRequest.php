<?php

namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;

class UserHandleCommonAuditPasswordRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_audit_passwords_lists,id',
            'type' => 'required|numeric', //1登录密码 2 资金密码
            'status' => 'required|numeric', //1 通过 2 拒绝
            'auditor_note' => 'required', //密码审核备注
        ];
    }

    /*public function messages()
{
return [
'lottery_sign.required' => 'lottery_sign is required!',
'trace_issues.required' => 'trace_issues is required!',
'balls.required' => 'balls is required!'
];
}*/
}
