<?php

namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Support\Facades\Config;

class LotteriesInputCodeRequest extends BaseFormRequest
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
        // $lottery_id = $this->get('lottery_id');
        // $lenght = LotteryList::where('en_name', $lottery_id)->value('code_length');
        $rules = [
            'lottery_id' => 'required|string|exists:lottery_lists,en_name',
            'code' => 'required|string', //|size:' . $lenght,
            'issue' => 'required|numeric',
        ];
        return $rules;
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
