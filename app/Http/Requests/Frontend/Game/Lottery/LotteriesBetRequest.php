<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/5/2019
 * Time: 8:06 PM
 */

namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;
use App\Rules\Frontend\Lottery\Bet\BallsCodeRule;
use App\Rules\Frontend\Lottery\Bet\MethodCountsRule;
use Exception;

class LotteriesBetRequest extends BaseFormRequest
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
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'lottery_sign' => 'required|string|min:4|max:10|exists:lottery_lists,en_name',
            'trace_issues.*' => 'required|integer|between:1,1000',
//            'balls'=>[
//                new BallsCodeRule($this->get('lottery_sign'), $this->get('balls'))
//            ],
            'balls.*.method_id' => 'required|exists:lottery_methods,method_id',
            'balls.*.method_group' => 'required|exists:lottery_methods,method_group',
            'balls.*.method_name' => 'required',
            'balls.*.codes' =>
                [
                    new BallsCodeRule($this->get('lottery_sign'), $this->get('balls'))
                ],
            'balls.*.count' => [
                'required',
                'integer',
                new MethodCountsRule($this->get('balls'))
            ],
            'balls.*.times' => 'required|integer',
            'balls.*.cost' => 'required|regex:/^\d+(\.\d{1,3})?$/',
            'balls.*.mode' => 'required|regex:/^\d+(\.\d{1,3})?$/|in:1.000,0.100,0.010,0.001',
            //float '1.000', '0.100', '0.010', '0.001'
            'balls.*.prize_group' => 'required|integer',
            'balls.*.price' => 'required|integer|in:1,2',
            'balls.*.challenge'=> 'required|integer|in:0,1',
            'balls.*.challenge_prize'=> 'required|integer|between:0,40000',
            'trace_win_stop' => 'required|integer',
            'total_cost' => 'required|regex:/^\d+(\.\d{1,3})?$/',
            'from' => 'integer',
            'is_trace' => 'required|integer|in:0,1',
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
        return [
            'balls' => 'cast:array',
            'trace_issues' => 'cast:array',
        ];
    }
}
