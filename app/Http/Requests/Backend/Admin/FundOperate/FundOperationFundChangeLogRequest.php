<?php

namespace App\Http\Requests\Backend\Admin\FundOperate;

use App\Http\Requests\BaseFormRequest;

class FundOperationFundChangeLogRequest extends BaseFormRequest
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
            'admin_id' => 'required|numeric|exists:backend_admin_users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
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
