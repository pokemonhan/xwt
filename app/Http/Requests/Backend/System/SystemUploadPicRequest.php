<?php

namespace App\Http\Requests\Backend\System;

use App\Http\Requests\BaseFormRequest;

class SystemUploadPicRequest extends BaseFormRequest
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
            'pic' => 'required|image|mimes:jpeg,png,jpg',
            'folder_name' => 'required|string|min:2|max:30',
        ];
    }
}
