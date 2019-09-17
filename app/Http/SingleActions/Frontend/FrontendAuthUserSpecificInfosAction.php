<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;

class FrontendAuthUserSpecificInfosAction
{
    /**
     * 用户个人信息
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $usersSpecificInfoEloq = $contll->partnerUser->specific;
        $data = [
            'nickname' => null,
            'realname' => null,
            'mobile' => null,
            'email' => null,
            'zip_code' => null,
            'address' => null,
        ];
        if ($usersSpecificInfoEloq !== null) {
            $data = [
                'nickname' => $usersSpecificInfoEloq->nickname,
                'realname' => $usersSpecificInfoEloq->realname,
                'mobile' => $usersSpecificInfoEloq->mobile,
                'email' => $usersSpecificInfoEloq->email,
                'zip_code' => $usersSpecificInfoEloq->zip_code,
                'address' => $usersSpecificInfoEloq->address,
            ];
        }

        return $contll->msgOut(true, $data);
    }
}
