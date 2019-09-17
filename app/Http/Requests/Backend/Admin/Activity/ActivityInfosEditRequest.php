<?php

namespace App\Http\Requests\Backend\Admin\Activity;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ActivityInfosEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_activity_contents',
            'title' => ['required', Rule::unique('frontend_activity_contents')->ignore($this->get('id'))], //标题
            'content' => 'required|string', //内容
            'pic' => 'image|mimes:jpeg,png,jpg', //活动图片
            'preview_pic' => 'image|mimes:jpeg,png,jpg', //预览图
            'start_time' => 'date_format:Y-m-d H:i:s|required_if:is_time_interval,1', //开始时间
            'end_time' => 'date_format:Y-m-d H:i:s|required_if:is_time_interval,1', //结束时间
            'status' => 'required|integer|in:0,1', //开启状态 0关闭 1开启
            'is_redirect' => 'required|integer|in:0,1', //是否跳转 0不跳转 1跳转
            'redirect_url' => 'required_if:is_redirect,1', //跳转地址
            'is_time_interval' => 'required|numeric', //是否有期限 0无期限 1有期限
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
