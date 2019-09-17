<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class FrontendAuthSetFundPasswordAction
{
    /**
     * 用户设置资金密码
     * @param  FrontendApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        if ($contll->partnerUser->fund_password !== null) {
            return $contll->msgOut(false, [], '100013');
        }
        //检验设置资金密码与用户密码不能一致
        if (Hash::check($inputDatas['password'], $contll->partnerUser->password)) {
            return $contll->msgOut(false, [], '100024');
        }
        try {
            $partnerUserEloq = $contll->partnerUser;
            $partnerUserEloq->fund_password = Hash::make($inputDatas['password']);
            $partnerUserEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $exception) {
            return $contll->msgOut(false, [], $exception->getCode(), $exception->getMessage());
        }
    }
}
