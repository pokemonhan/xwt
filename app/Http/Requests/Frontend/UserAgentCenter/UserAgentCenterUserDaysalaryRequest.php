<?php

namespace App\Http\Requests\Frontend\UserAgentCenter;

use App\Http\Requests\BaseFormRequest;

/**
 * 用户日工资报表
 */
class UserAgentCenterUserDaysalaryRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
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
            'username' => 'filled|string|alpha_dash',
            'date'=>'filled|date_format:Y-m-d',
            'count' => 'filled|integer',
            'user_type' => 'required|in:0,1,2', //用户属性 (0全部 1自己 2下级)
        ];
    }
}
