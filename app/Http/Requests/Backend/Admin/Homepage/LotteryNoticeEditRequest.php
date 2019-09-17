<?php

namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class LotteryNoticeEditRequest extends BaseFormRequest
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
            'id' => 'required|exists:frontend_lottery_notice_lists',
            'lotteries_id' => ['exists:lottery_lists,en_name', Rule::unique('frontend_lottery_notice_lists')->ignore($this->get('id'))], //彩种标识
            'cn_name' => ['exists:lottery_lists,cn_name', Rule::unique('frontend_lottery_notice_lists')->ignore($this->get('id'))], //彩种中文名
            'status' => 'integer|in:0,1', //开启状态：0关闭 1开启
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
