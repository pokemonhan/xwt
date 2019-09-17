<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\LotteryTrace;
use Illuminate\Http\JsonResponse;

class LotteriesTracesHistoryAction
{
    /**
     * 游戏-追号历史
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $eloqM = new LotteryTrace();
        $contll->inputs['user_id'] = $contll->partnerUser->id;
        $searchAbleFields = ['user_id', 'lottery_sign', 'status'];
        $fixedJoin = 2;
        $withTable = [
            'traceLists',
            'lottery:en_name,icon_path'
        ];
        $withSearchAbleFields = [
            ['project_serial_number', 'issue'],
            []
        ];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $data = $contll->generateSearchQuery(
            $eloqM,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            $withSearchAbleFields,
            $orderFields,
            $orderFlow
        );
        return $contll->msgOut(true, $data);
    }
}
