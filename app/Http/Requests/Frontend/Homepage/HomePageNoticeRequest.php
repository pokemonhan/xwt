<?php

namespace App\Http\Requests\Frontend\Homepage;

use App\Http\Requests\BaseFormRequest;

/**
 * 公告&站内信
 */
class HomePageNoticeRequest extends BaseFormRequest
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
            'type' => 'required|integer',
        ];
    }
}
