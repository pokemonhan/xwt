<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class FrontendAuthUserDetailAction
{
    /**
     * 用户信息
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $user = $contll->currentAuth->user();
        $account = $user->account;
        $balance = $account->balance;
        $frozen = $account->frozen;
        $minPrizeGroup = $contll->minClassicPrizeGroup; //最低奖金组
        $unreadMessageNum = $user->unreadMessageNum();//未读数量
        $data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'min_prize_group' => $minPrizeGroup,
            'prize_group' => $user->prize_group,
            'user_type' => $user->type,
            'is_tester' => $user->is_tester,
            'last_login_time' => $user->last_login_time->toDateTimeString(),
            'levels' => $user->levels,
            'can_withdraw' => $user->frozen_type <= 0, //$user->frozen_type > 0 ? false : true
            'today_withdraw' => 0, //当日已提款金额
            'daysalary_percentage' => 0,
            'bonus_percentage' => 0,
            'allowed_transfer' => true,
            'balance' => sprintf('%1.4f', substr($balance, 0, strrpos($balance, '.') + 1 + 4)),
            'frozen_balance' => sprintf('%1.4f', substr($frozen, 0, strrpos($frozen, '.') + 1 + 4)),
            'has_funds_password' => $user->fund_password ? true : false,
            'max_profit_bonus' => configure('max_profit_bonus'),
            'download_url' => configure('app_download_url') . '/' . $user->invite_code,
            'version' => configure('app_version'),
            'pic_path' => $user->pic_path,
            'fund_password' => $user->fund_password !== null,
            'unread_message_num' => $unreadMessageNum,
        ];
        return $contll->msgOut(true, $data);
    }
}
