<?php

namespace App\Http\Requests\Frontend\UserAgentCenter;

use App\Http\Requests\BaseFormRequest;

class UserAgentCenterTeamReportRequest extends BaseFormRequest
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
            'parent_id' => 'integer',//父级id
            'username' => 'string',//username
            'time_condtions' => 'string', //时间
            'type' => 'integer', //报表类型
            'page_size' => 'integer|between:10,30',
        ];
    }

    /*public function messages()
    {
        return [
            'min_team_balance.required_with' => '缺少最小团队余额',
        ];
    }*/
}
