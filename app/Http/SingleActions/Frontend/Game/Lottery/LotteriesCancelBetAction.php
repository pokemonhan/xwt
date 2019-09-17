<?php

namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LotteriesCancelBetAction
{
    /**
     * 投注撤单
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $projectEloq = Project::find($inputDatas['id']);
        if ($projectEloq->user_id !== $contll->partnerUser->id) {
            return $contll->msgOut(false, [], '100314');
        }
        if ($projectEloq->status !== Project::STATUS_NORMAL) {
            return $contll->msgOut(false, [], '100316');
        }
        DB::beginTransaction();
        $projectEloq->status = Project::STATUS_DROPED;
        $projectEloq->save();
        if ($projectEloq->errors()->messages()) {
            DB::rollback();
            return $contll->msgOut(false, [], '400', $projectEloq->errors()->messages());
        }
        //帐变处理
        $user = $contll->partnerUser;
        if ($user->account()->exists()) {
            $account = $user->account;
        } else {
            return $contll->msgOut(false, [], '100313');
        }
        $params = [
            'user_id' => $user->id,
            'amount' => $projectEloq->total_cost,
            'lottery_id' => $projectEloq->lottery_sign,
            'method_id' => $projectEloq->method_sign,
            'project_id' => $projectEloq->id,
            'issue' => $projectEloq->issue,
        ];
        $res = $account->operateAccount($params, 'cancel_order');
        if ($res !== true) {
            DB::rollBack();
            return $contll->msgOut(false, [], '', $res);
        }
        DB::commit();
        return $contll->msgOut(true);
    }
}
