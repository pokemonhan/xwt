<?php

namespace App\Http\Requests\Backend\Report;

use App\Http\Requests\BaseFormRequest;

class ReportManagementWithdrawRecordRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() :bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() :array
    {
        return [
            'username' => 'string', //用户名
            'status' => 'integer', //状态
            'order_id' => 'string', //编号
            'is_tester' => 'integer', //是否是测试用户
        ];
    }
}
