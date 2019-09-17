<?php
/**
 * @Author: Fish
 * @Date:   2019/7/4 18:46
 */

namespace App\Http\Requests\Backend\Admin\Help;

use App\Http\Requests\BaseFormRequest;

class HelpCenterEditRequest extends BaseFormRequest
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
            'id'        => 'required|numeric|exists:frontend_users_help_centers,id',
            'pid'       => 'required|numeric',
            'menu'      => 'required|string|max:32',
            'status'    => 'required|numeric',
            'content'   => 'nullable',
        ];
    }
}
