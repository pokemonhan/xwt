<?php

namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class PopularMethodsEditRequest extends BaseFormRequest
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
            'id' => 'required|exists:frontend_lottery_fnf_betable_lists,id',
            'lotteries_id' => [
                'required',
                'exists:lottery_lists,en_name',
                Rule::unique('frontend_lottery_fnf_betable_lists')->ignore($this->get('id'))
            ],
            'method_id' => [
                'required',
                'exists:frontend_lottery_fnf_betable_methods,id',
                Rule::unique('frontend_lottery_fnf_betable_lists')->ignore($this->get('id'))
            ],
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
