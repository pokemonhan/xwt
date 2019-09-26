<?php
namespace App\Http\Requests\Frontend\Game\Casino;

use App\Http\Requests\BaseFormRequest;

/**
 * Class CasinoSearchRequest
 * @package App\Http\Requests\Frontend\Game\Casino
 */
class CasinoSearchRequest extends BaseFormRequest
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
            'categorie' => 'required|string|min:4|max:10', //lottery_lists
            'platCode' => 'required|string|min:2|max:10', //lottery_lists
            'gameCode'  => 'required|string|min:2',
        ];
    }
}
