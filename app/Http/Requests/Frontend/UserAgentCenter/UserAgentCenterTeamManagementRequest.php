<?php

namespace App\Http\Requests\Frontend\UserAgentCenter;

use App\Http\Requests\BaseFormRequest;

class UserAgentCenterTeamManagementRequest extends BaseFormRequest
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
            'parent_id' => 'integer',
            'username' => 'string',
            'time_condtions' => 'string', //注册时间
            'price_group_condtions' => 'string', //奖金组
            'min_team_balance' => 'numeric|required_with:max_team_balance', //最小团队余额
            'max_team_balance' => 'numeric|required_with:min_team_balance', //最大团队余额
            'page_size' => 'integer|between:10,30',
        ];
    }
    public function messages()
    {
        return [
            'min_team_balance.required_with' => '缺少最小团队余额',
            'max_team_balance.required_with' => '缺少最大团队余额',
        ];
    }
}
