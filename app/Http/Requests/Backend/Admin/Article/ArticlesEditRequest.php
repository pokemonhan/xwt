<?php

namespace App\Http\Requests\Backend\Admin\Article;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class ArticlesEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_message_articles,id',
            'category_id' => 'required|numeric|exists:frontend_info_categories,id', //文章分类的id
            'title' => ['required', 'string', Rule::unique('frontend_activity_contents')->ignore($this->get('id'))], //标题
            'summary' => 'required|string', //描述
            'content' => 'required|string', //内容
            'search_text' => 'required|string', //查询关键字
            'is_for_agent' => 'required|in:0,1', //是否代理专属
            'apply_note' => 'required|string', //备注
            'pic_name' => 'array',
            'pic_path' => 'array',
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
