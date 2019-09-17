<?php

namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesProjectHistoryRequest extends BaseFormRequest
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
        //############################需要做验证处理
        return [
            // 'page_size' => 'string', //'integer|min:10|max:100',
            // 'lottery_sign' => 'string', //'string|exists:lottery_lists,en_name',
            // 'issue' => 'string', //'alpha_num',
            // 'status' => 'string', //'',
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
