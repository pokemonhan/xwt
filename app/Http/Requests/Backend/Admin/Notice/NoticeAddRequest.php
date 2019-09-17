<?php

namespace App\Http\Requests\Backend\Admin\Notice;

use App\Http\Requests\BaseFormRequest;

class NoticeAddRequest extends BaseFormRequest
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
            'type' => 'required|numeric', // 1公告 2站内信
            // 'receive_user' => 'required_if:type,2',
            'title' => 'required|string', // 标题
            'describe' => 'required|string', //简介
            'content' => 'required|string', // 内容
            'pic_name' => 'string', //图片名称
            'pic_path' => 'string', //图片路径
            'status' => 'required|string', //1显示 0隐藏
            'start_time' => 'required_if:type,1|date_format:Y-m-d H:i:s', //（公告）开始时间
            'end_time' => 'required_if:type,1|date_format:Y-m-d H:i:s', //（公告）结束时间
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
