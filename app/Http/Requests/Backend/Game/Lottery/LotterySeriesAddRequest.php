<?php

namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotterySeriesAddRequest extends BaseFormRequest
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
            'series_name' => 'required|alpha_num|unique:lottery_series', //系列标识
            'title' => 'required|string|unique:lottery_series', //系列名称
            'status' => 'required|integer|in:0,1', //状态:0关闭  1开启
            'encode_splitter' => 'nullable', //开奖号码分隔符
            'price_difference' => 'required|integer', //差价
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
