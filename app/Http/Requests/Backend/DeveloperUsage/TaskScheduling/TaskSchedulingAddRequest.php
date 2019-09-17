<?php

namespace App\Http\Requests\Backend\DeveloperUsage\TaskScheduling;

use App\Http\Requests\BaseFormRequest;

class TaskSchedulingAddRequest extends BaseFormRequest
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
            'command' => 'required|string', //任务名
            'param' => 'required|string', //传递的参数 Arguments 或者 options
            'schedule' => 'required|string', //时间的cron表达式（* * * * *）   Cron 命令pattern
            'status' => 'required|integer|in:0,1', //任务状态：0关闭 1开启
            'remarks' => 'required|string', //定时任务用意描述备注
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
