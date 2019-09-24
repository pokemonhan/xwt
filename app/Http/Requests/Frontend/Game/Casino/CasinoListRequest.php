<?php
namespace App\Http\Requests\Frontend\Game\Casino;

use App\Http\Requests\BaseFormRequest;

/**
 * Class CasinoListRequest
 * @package App\Http\Requests\Frontend\Game\Casino
 */
class CasinoListRequest extends BaseFormRequest
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
            'categorie' => 'required|string|min:4|max:10|exists:casino_game_categories,code', //lottery_lists
            'platCode' => 'required|string|min:2|max:10|exists:casino_game_platforms,main_game_plat_code', //lottery_lists
        ];
    }
}