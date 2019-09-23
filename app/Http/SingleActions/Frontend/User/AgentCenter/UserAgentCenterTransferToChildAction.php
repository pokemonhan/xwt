<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\FrontendUsersBankCard;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $user = (object) [];
        $child = (object) [];
        $userAccount = null;
        $childAccount = null;

        $verification = $this->verification($contll, $inputDatas, $user, $child, $userAccount, $childAccount);
        if ($verification['success'] === false) {
            return $contll->msgOut(false, [], $verification['code']);
        }

        DB::beginTransaction();
        try {
            //扣除上级用户金额
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
            //增加下级用户金额
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

    /**
     * 验证信息
     * @param FrontendApiMainController $contll       Controller.
     * @param array                     $inputDatas   传递的参数.
     * @param object                    $user         上级用户.
     * @param object                    $child        下级用户.
     * @param mixed                     $userAccount  上级用户的Account表.
     * @param mixed                     $childAccount 下级用户的Account表.
     * @return array
     */
    private function verification(
        FrontendApiMainController $contll,
        array $inputDatas,
        object &$user,
        object &$child,
        &$userAccount,
        &$childAccount
    ) {
        //接受转账的用户
        $child = $this->model::find($inputDatas['user_id']);
        if ($child === null) {
            return $this->returnDataArr(false, '100604');
        }
        //是否是下级
        $user = $contll->partnerUser;
        $userRidArr = explode('|', $child->rid);
        if (!in_array($user->id, $userRidArr)) {
            return $this->returnDataArr(false, '100605');
        }
        //不能自己给自己转账
        if ($child->id === $user->id) {
            return $this->returnDataArr(false, '100607');
        }
        //用户的Account表
        $userAccount = $user->account;
        if ($userAccount === null) {
            return $this->returnDataArr(false, '100606');
        }
        //接受转账的用户Account表
        $childAccount = $child->account;
        if ($childAccount === null) {
            return $this->returnDataArr(false, '100608');
        }
        //资金密码
        if (!Hash::check($inputDatas['fund_password'], $contll->partnerUser->fund_password)) {
            return $this->returnDataArr(false, '100610');
        }
        //银行卡验证
        $bankCard = FrontendUsersBankCard::where([
            ['id', $inputDatas['bank_card_id']],
            ['user_id', $contll->partnerUser->id],
        ])->first();
        if ($bankCard === null) {
            return $this->returnDataArr(false, '100611');
        }
        if ((string) $inputDatas['bank_card_number'] !== $bankCard->card_number) {
            return $this->returnDataArr(false, '100612');
        }
    }

    /**
     * 拼装验证返回信息arr
     * @param  boolean $success 状态.
     * @param  string  $code    返回信息编码.
     * @return array
     */
    private function returnDataArr(bool $success, string $code)
    {
        return ['success' => $success, 'code' => $code];
    }
}
