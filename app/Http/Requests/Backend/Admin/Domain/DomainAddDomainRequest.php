<?php

namespace App\Http\Requests\Backend\Admin\Domain;

use App\Http\Requests\BaseFormRequest;

/**
 * Class DomainAddRequest
 * @package App\Http\Requests\Backend\Admin\Domain
 */
class DomainAddDomainRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.git
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
            'user_id' => 'numeric',
            'domain' => 'required|string',
            'config_id' => 'numeric',
            'is_use' => 'numeric',
        ];
    }
}
