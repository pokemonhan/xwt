<?php

namespace App\Http\Requests\Backend\Users\Fund;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class AccountChangeTypeEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_users_accounts_types',
            'name' => 'required|string',
            'sign' => ['required', 'string', Rule::unique('frontend_users_accounts_types')->ignore($this->get('id'))],
            'in_out' => 'required|numeric|in:0,1',
            'param' => 'array',
            'param.*' => 'exists:frontend_users_accounts_types_params,id',
            'frozen_type' => 'required|numeric|in:0,1', //冻结类型
            'activity_sign' => 'required|numeric|in:0,1', //活动相关
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
