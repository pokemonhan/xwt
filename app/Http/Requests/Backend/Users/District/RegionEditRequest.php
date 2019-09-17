<?php

namespace App\Http\Requests\Backend\Users\District;

use App\Http\Requests\BaseFormRequest;

class RegionEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:users_regions,id',
            'region_parent_id' => 'required|numeric|exists:users_regions,region_id',
            'region_id' => 'required|numeric',
            'region_name' => 'required',
            'region_level' => 'required|in:1,2,3,4',
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
