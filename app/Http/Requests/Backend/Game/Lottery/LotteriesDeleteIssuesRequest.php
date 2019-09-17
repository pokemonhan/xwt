<?php

namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesDeleteIssuesRequest extends BaseFormRequest
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
            'type' => 'required|integer|in:1,2', // 1：按id删除     2：删除某个彩种一天的所有奖期
            'id' => 'required_if:type,1|array',
            'id.*' => 'exists:lottery_issues,id',
            'lottery' => 'required_if:type,2|exists:lottery_issue_rules,lottery_id', //彩种标识
            'day' => 'required_if:type,2|date_format:Ymd', //日期
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
