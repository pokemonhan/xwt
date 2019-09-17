<?php

namespace App\Http\Requests\Backend\Admin\DynActivity;

use App\Http\Requests\BaseFormRequest;
use App\Rules\Alpha;
use App\Rules\AlphaNum;

class DynActivityAddRequest extends BaseFormRequest
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
            'name' => 'required|unique:backend_dyn_activity_lists,name', //活动名字
            'uname' => ['required',new Alpha(),'unique:backend_dyn_activity_lists,uname'], //活动别名
            'pc_pic' => 'image|mimes:jpeg,png,jpg', //pc端活动导入图
            'wap_pic' => 'image|mimes:jpeg,png,jpg', //wap端活动导入图
            'rule_file' => [new AlphaNum()], //活动规则文件指针
            'start_time' => 'required|date_format:Y-m-d H:i:s', //开始时间
            'end_time' => 'required|date_format:Y-m-d H:i:s', //结束时间
            'sort' => 'integer', //排序
            'status' => 'integer|in:0,1', //活动的状态
        ];
    }
}
