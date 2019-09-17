<?php

namespace App\Http\Requests\Backend\Report;

use App\Http\Requests\BaseFormRequest;

class ReportManagementUserAccountChangeRequest extends BaseFormRequest
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
            'serial_number' => 'string', //编号
            'username' => 'string', //用户名
            'type_sign' => 'alpha_dash', //帐变类型标识
            'in_out' => 'integer|in:1,2', //出入类型 1增加 2减少
            'is_tester' => 'integer|in:0,1', //是否测试用户 0否  1是
            'get_sub' => 'required|integer|in:0,1', //是否查询下级(0否   1是)
            'ip' => 'ip', //ip
            'from_admin_id' => 'integer', //管理员id
            'min_price' => 'numeric', //最小金额
            'max_price' => 'numeric', //最大金额
            'lottery_id' => 'string' //彩种标识
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
