<?php

namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;

/**
 * 设置用户分红比例
 */
class UserHandleSetBonusPercentageRequest extends BaseFormRequest
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
            'user_id' => 'required|numeric|exists:frontend_users,id',
            'bonus_percentage' => 'required|numeric|between:0,100', //分红比例
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
