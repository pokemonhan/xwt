<?php
namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\SystemConfiguration;
use Illuminate\Http\JsonResponse;

class UserAgentCenterPrizeGroupAction
{

    /**
     * 下级开户，最大最小奖金组信息
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = [];
        //最低开户奖金组
        $data['min_user_prize_group'] = configure('min_user_prize_group');
        //最高开户奖金组
        $data['max_user_prize_group'] = configure('max_user_prize_group');

        $userInfo = $contll->currentAuth->user();
        if ($userInfo->prize_group < $data['max_user_prize_group']) {
            $data['max_user_prize_group'] = $userInfo->prize_group;
        }
        return $contll->msgOut(true, $data);
    }
}
