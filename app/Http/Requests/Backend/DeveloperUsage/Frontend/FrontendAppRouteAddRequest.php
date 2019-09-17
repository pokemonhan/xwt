<?php

namespace App\Http\Requests\Backend\DeveloperUsage\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAppRouteAddRequest extends BaseFormRequest
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
            'route_name' => 'required|string|unique:frontend_app_routes,route_name',
            'controller' => 'required|string',
            'method' => 'required|string',
            'frontend_model_id' => 'required|numeric|exists:frontend_allocated_models,id',
            'title' => 'required|string',
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
