<?php

namespace App\Http\Requests\Backend\Users\Fund;

use App\Http\Requests\BaseFormRequest;

/**
 * 添加帐变类型
 */
class AccountChangeTypeAddRequest extends BaseFormRequest
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
            'name' => 'required|string',
            'sign' => 'required|string|unique:frontend_users_accounts_types',
            'in_out' => 'required|numeric|in:1,2',
            'param' => 'array',
            'param.*' => 'exists:frontend_users_accounts_types_params,id',
            'frozen_type' => 'required|numeric|in:0,1',
            'activity_sign' => 'required|numeric|in:0,1',
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
