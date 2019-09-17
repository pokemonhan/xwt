<?php

namespace App\Http\SingleActions\Frontend\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\Fund\FrontendUsersBankCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserBankCardTwoAddVerifiyAction
{
    protected $model;
    /**
     * @param  FrontendUsersBankCard  $frontendUsersBankCard
     */
    public function __construct(FrontendUsersBankCard $frontendUsersBankCard)
    {
        $this->model = $frontendUsersBankCard;
    }

    /**
     * 用户二次添加绑定银行卡
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $ownerNameNum = $this->model::where([['owner_name', $inputDatas['owner_name']],['user_id',$contll->partnerUser->id]])->count();
        if ($ownerNameNum === 0) {
            return $contll->msgOut(false, [], '100207');
        }
        if (!Hash::check($inputDatas['fund_password'], $contll->partnerUser->fund_password)) {
            return $contll->msgOut(false, [], '100206');
        }
        return $contll->msgOut(true, [], '100209');
    }
}
