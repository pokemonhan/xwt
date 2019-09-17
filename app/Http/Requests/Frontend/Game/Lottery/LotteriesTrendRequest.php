<?php

namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesTrendRequest extends BaseFormRequest
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
            'lottery_id' => 'required|string', //lottery_lists
            'num_type' => 'required|alpha_num|max:3', //玩法类型
            'count' => 'required|integer|in:30,50,100', //多少条
//            'begin_time' => 'required|date', //开始时间
//            'end_time' => 'required|date', //结束时间
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            'lottery_id' => 'trim|escape', //彩种id
            'num_type' => 'trim|escape', //玩法类型
            'count' => 'trim|escape|cast:integer', //多少条
//            'begin_time' => 'trim|escape', //开始时间
//            'end_time' => 'trim|escape', //结束时间
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
