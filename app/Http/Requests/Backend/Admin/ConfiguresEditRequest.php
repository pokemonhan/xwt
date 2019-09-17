<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ConfiguresEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:system_configurations,id',
            'parent_id' => 'required|numeric',
            'sign' => ['required', Rule::unique('system_configurations')->ignore($this->get('id'))],
            'name' => 'required',
            'description' => 'required',
            'value' => 'string',
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
