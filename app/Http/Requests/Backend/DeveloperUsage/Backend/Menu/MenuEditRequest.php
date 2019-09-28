<?php

namespace App\Http\Requests\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Requests\BaseFormRequest;

/**
 * Class MenuEditRequest
 * @package App\Http\Requests\Backend\DeveloperUsage\Backend\Menu
 */
class MenuEditRequest extends BaseFormRequest
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
            'label' => 'required|regex:/[\x{4e00}-\x{9fa5}]+/u', //操作日志
            'en_name' => 'required|regex:/^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-z.-]+$/', //operation.log
            'display' => 'required|numeric|in:0,1',
            'menuId' => 'required|numeric',
            'route' => 'required|regex:/^(?!.*\/$)(?!.*?\/\/)[a-z\/-]+$/', // /operasyon/operation-log
            'icon' => 'regex:/^(?!\-)(?!.*\-$)(?!.*?\-\-)(?!\ )(?!.*\ $)(?!.*?\ \ )[a-z0-9 -]+$/',
            'isParent' => 'numeric|in:0,1',
            'parentId' => 'numeric|required_unless:isParent,1',
            'type' => 'required|integer|in:1,2,3', //1 代表菜单要在左侧显示 2 代表按钮  3 代表普通链接
            //anticon anticon-appstore  icon-6-icon
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
