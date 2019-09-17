<?php

namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class LotteriesEditRequest extends BaseFormRequest
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
            //lottery
            'lottery' => 'required|array', //
            'lottery.id' => 'required|exists:lottery_lists,id', //
            'lottery.lottery_type' => 'required|in:1,2', //1开奖号码可重复  2开奖号码不可重复
            'lottery.cn_name' => ['required', 'string', Rule::unique('lottery_lists', 'cn_name')->ignore($this->get('lottery')['id'])], //中文名
            'lottery.en_name' => ['required', 'alpha_num', Rule::unique('lottery_lists', 'en_name')->ignore($this->get('lottery')['id'])], //英文名
            'lottery.series_id' => 'required|alpha_num', //彩种系列
            'lottery.is_fast' => 'required|in:0,1', //是否快彩
            'lottery.max_trace_number' => 'required|integer', //最大追号期数
            'lottery.day_issue' => 'required|integer', //每日开奖期数
            'lottery.issue_format' => 'required|string', //奖期格式
            'lottery.issue_type' => 'required|string', //
            'lottery.valid_code' => 'required|string', //合法开奖号码
            'lottery.code_length' => 'required|integer', //开奖号码长度
            'lottery.positions' => 'required|string', //
            'lottery.min_prize_group' => 'required|integer', //最小奖金组
            'lottery.max_prize_group' => 'required|integer', //最大奖金组
            'lottery.min_times' => 'required|integer', //下注最小倍数
            'lottery.max_times' => 'required|integer', //下注最大倍数
            'lottery.max_profit_bonus' => 'required|numeric', //最大盈利奖金
            'lottery.valid_modes' => 'required|string', //
            'lottery.status' => 'required|in:0,1', //状态：0关闭 1开启
            'lottery.icon_name' => 'string', //彩种图标名称
            'lottery.icon_path' => 'required|string', //彩种图标路径
            //issue_rule 1
            'issue_rule' => 'required|array', //
            'issue_rule.0.id' => 'required|exists:lottery_issue_rules,id', //id
            'issue_rule.0.lottery_id' => 'required|same:lottery.en_name', //英文名
            'issue_rule.0.lottery_name' => 'required|same:lottery.cn_name', //中文名
            'issue_rule.0.begin_time' => 'required|date_format:H:i:s', //开始时间
            'issue_rule.0.end_time' => 'required|date_format:H:i:s', //结束时间
            'issue_rule.0.issue_seconds' => 'required|integer|gte:60', //奖期周期（秒）
            'issue_rule.0.first_time' => 'required|date_format:H:i:s', //首期结束时间
            'issue_rule.0.adjust_time' => 'required|integer', //调整截止时间（秒）
            'issue_rule.0.encode_time' => 'required|integer',
            'issue_rule.0.issue_count' => 'required|integer',
            'issue_rule.0.status' => 'required|same:lottery.status', //状态：0关闭 1开启
            //issue_rule 2    重庆时时彩存在2条奖期规则
            'issue_rule.1.id' => 'required_with:issue_rule.1|exists:lottery_issue_rules,id', //id
            'issue_rule.1.lottery_id' => 'required_with:issue_rule.1|same:lottery.en_name', //英文名
            'issue_rule.1.lottery_name' => 'required_with:issue_rule.1|same:lottery.cn_name', //中文名
            'issue_rule.1.begin_time' => 'required_with:issue_rule.1|date_format:H:i:s', //开始时间
            'issue_rule.1.end_time' => 'required_with:issue_rule.1|date_format:H:i:s', //结束时间
            'issue_rule.1.issue_seconds' => 'required_with:issue_rule.1|integer', //奖期周期（秒）
            'issue_rule.1.first_time' => 'required_with:issue_rule.1|date_format:H:i:s', //首期结束时间
            'issue_rule.1.adjust_time' => 'required_with:issue_rule.1|integer', //调整截止时间（秒）
            'issue_rule.1.encode_time' => 'required_with:issue_rule.1|integer',
            'issue_rule.1.issue_count' => 'required_with:issue_rule.1|integer',
            'issue_rule.1.status' => 'required_with:issue_rule.1|same:lottery.status', //状态：0关闭 1开启
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
