<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class FrontendCommonHandleUserPasswordAction
{
    /**
     * 修改 用户密码1 资金密码2 共用处理
     * @param array $inputDatas
     * @param int $type
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas, $type): JsonResponse
    {
        $targetUserEloq = $contll->eloqM::find($contll->partnerUser->id);
        if ($type === 1) {
            $field = 'password';
            $oldPassword = $targetUserEloq->password;
            //检验用户新密码与资金密码不能一致
            if (Hash::check($inputDatas['password'], $targetUserEloq->fund_password)) {
                return $contll->msgOut(false, [], '100025');
            }
        } elseif ($type === 2) {
            $field = 'fund_password';
            $oldPassword = $targetUserEloq->fund_password;
            //检验资金新密码与用户密码不能一致
            if (Hash::check($inputDatas['password'], $targetUserEloq->password)) {
                return $contll->msgOut(false, [], '100024');
            }
        } else {
            return $contll->msgOut(false, [], '100010');
        }
        //校验密码
        if (!Hash::check($inputDatas['old_password'], $oldPassword)) {
            return $contll->msgOut(false, [], '100009');
        }
        //修改密码
        $targetUserEloq->$field = Hash::make($inputDatas['password']);
        if ($targetUserEloq->save()) {
            if ($type === 1) {
                // $targetUserEloq->remember_token = $token;
                $this->refresh($contll); //修改登录密码更新token
            }
            return $contll->msgOut(true);
        } else {
            return $contll->msgOut(false, [], '100011');
        }
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(FrontendApiMainController $contll)
    {
        return $contll->currentAuth->refresh();
    }
}
