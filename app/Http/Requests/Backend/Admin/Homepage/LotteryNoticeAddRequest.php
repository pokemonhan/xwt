<?php

namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;

class LotteryNoticeAddRequest extends BaseFormRequest
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
            'lotteries_id' => 'required|exists:lottery_lists,en_name|unique:frontend_lottery_notice_lists', //彩种标识
            'cn_name' => 'required|exists:lottery_lists,cn_name|unique:frontend_lottery_notice_lists', //彩种中文名
            'status' => 'required|integer|in:0,1', //开启状态：0关闭 1开启
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
