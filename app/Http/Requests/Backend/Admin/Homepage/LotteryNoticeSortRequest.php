<?php

namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;

class LotteryNoticeSortRequest extends BaseFormRequest
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
            'front_id' => 'required|exists:frontend_lottery_notice_lists,id', //拖动排序完成后 最前面的数据id
            'rearways_id' => 'required|exists:frontend_lottery_notice_lists,id', //拖动排序完成后 最后面的数据id
            'front_sort' => 'required|numeric|gt:0', //拖动排序完成后 最前面的数据sort
            'rearways_sort' => 'required|numeric|gt:0', //拖动排序完成后 最后面的数据sort
            'sort_type' => 'required|numeric|in:1,2', //拖动类型 1上拉 2下拉
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
