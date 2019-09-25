<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/5/2019
 * Time: 8:06 PM
 */
namespace App\Http\Requests\Casino\Game;

use App\Http\Requests\BaseFormRequest;

/**
 * Class TransferInRequest
 * @package App\Http\Requests\Casino\Game
 */
class TransferInRequest extends BaseFormRequest
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
            'mainGamePlat' => 'required|string',
            'price' => 'required|numeric|min:1',
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
    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }
}
