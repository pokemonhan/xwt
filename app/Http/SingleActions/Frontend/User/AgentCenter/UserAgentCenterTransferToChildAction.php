<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUser;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * 转账给下级
 */
class UserAgentCenterTransferToChildAction
{
    /**
     * [Model]
     * @var FrontendUser
     */
    protected $model;

    /**
     * @param FrontendUser $frontendUser Model.
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * @param  FrontendApiMainController $contll     Controller.
     * @param  array                     $inputDatas 传递的参数.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $child = $this->model::find($inputDatas['user_id']);
        if ($child === null) {
            return $contll->msgOut(false, [], '100604');
        }
        $user = $contll->partnerUser;
        $userRidArr = explode('|', $child->rid);
        if (!in_array($user->id, $userRidArr)) {
            return $contll->msgOut(false, [], '100605');
        }
        if ($child->id === $user->id) {
            return $contll->msgOut(false, [], '100607');
        }
        $userAccount = $user->account;
        if ($userAccount === null) {
            return $contll->msgOut(false, [], '100606');
        }
        $childAccount = $child->account;
        if ($childAccount === null) {
            return $contll->msgOut(false, [], '100608');
        }

        DB::beginTransaction();
        try {
            $params = [
                'user_id' => $user->id,
                'to_id' => $child->id,
                'amount' => $inputDatas['amount'],
            ];
            $result = $userAccount->operateAccount($params, 'recharge_to_child');
            if ($result !== true) {
                DB::rollback();
                return $contll->msgOut(false, [], '', $result);
            }

            $params = [
                'user_id' => $child->id,
                'from_id' => $user->id,
                'amount' => $inputDatas['amount'],
            ];
            $result = $childAccount->operateAccount($params, 'recharge_from_parent');
            if ($result !== true) {
                DB::rollback();
                return $contll->msgOut(false, [], '', $result);
            }
            DB::commit();
            return $contll->msgOut(true, [], '100609');
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
