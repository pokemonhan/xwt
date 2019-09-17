<?php

namespace App\Http\Requests\Backend\DeveloperUsage\Frontend;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class FrontendAllocatedModelEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_allocated_models,id',
            'label' => ['required', 'string', Rule::unique('frontend_allocated_models')->ignore($this->get('id'))],
            'en_name' => ['required', 'string', Rule::unique('frontend_allocated_models')->ignore($this->get('id'))],
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
