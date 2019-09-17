<?php

namespace App\Http\Requests\Backend\DeveloperUsage\TaskScheduling;

use App\Http\Requests\BaseFormRequest;

class TaskSchedulingEditRequest extends BaseFormRequest
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
            'id' => 'required|exists:cron_jobs',
            'command' => 'string', //任务名
            'param' => 'string', //传递的参数 Arguments 或者 options
            'schedule' => 'string', //时间的cron表达式（* * * * *）   Cron 命令pattern
            'status' => 'integer|in:0,1', //任务状态：0关闭 1开启
            'remarks' => 'string', //定时任务用意描述备注
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
