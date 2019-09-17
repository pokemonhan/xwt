<?php

namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesStopTraceRequest extends BaseFormRequest
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
            'type' => 'required|integer|in:1,2', //1.停止所有追号  2.取消一期追号
            'lottery_traces_id' => 'required_if:type,1|integer|exists:lottery_traces,id', //lottery_traces表id
            'lottery_trace_lists_id' => 'required_if:type,2|integer|exists:lottery_trace_lists,id', //lottery_trace_lists表id
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
