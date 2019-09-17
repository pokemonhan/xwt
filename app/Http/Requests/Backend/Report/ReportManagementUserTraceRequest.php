<?php

namespace App\Http\Requests\Backend\Report;

use App\Http\Requests\BaseFormRequest;

class ReportManagementUserTraceRequest extends BaseFormRequest
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
            'trace_serial_number' => 'string', //编号
            'username' => 'string', //用户名
            'get_sub' => 'required|integer|in:0,1', //是否查询下级(0否   1是)
            'mode' => 'numeric', //金额模式(元:1.0000 , 角:0.1000 , 分 :0.0100)
            'status' => 'integer', //追号状态
            'lottery_sign' => 'alpha_dash', //彩种标识
            'method_sign' => 'alpha_dash', //玩法标识
            'issue' => 'alpha_dash', //奖期号
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
