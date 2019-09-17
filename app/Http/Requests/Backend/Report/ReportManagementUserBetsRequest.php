<?php

namespace App\Http\Requests\Backend\Report;

use App\Http\Requests\BaseFormRequest;

class ReportManagementUserBetsRequest extends BaseFormRequest
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
            'id' => 'integer',
            'serial_number' => 'alpha_num', //注单编号
            'username' => 'alpha_num|exists:frontend_users', //用户名称
            'get_sub' => 'required|in:0,1', //是否查询下级(0否   1是)
            'series_id' => 'alpha_num', //彩种系列
            'lottery_sign' => 'alpha_num', //彩种
            'method_sign' => 'alpha_num', //玩法
            'is_tester' => 'in:0,1', //是否测试用户 0否  1是
            'issue' => 'alpha_dash', //奖期号
            'status' => 'integer', //注单状态（0待开奖 1已撤销 2未中奖 3已中奖 4已派奖）
            'time_condtions' => 'string', //下注时间
            'times' => 'integer', //下注倍数
            'ip' => 'ip',
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
