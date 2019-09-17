<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class BackendAuthAllUserAction
{
    /**
     *
     * 获取所有当前平台的商户管理员用户
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        try {
            $data = $contll->currentPlatformEloq->partnerAdminUsers;
            if ($data === null) {
                $result = Arr::wrap($data);
            } else {
                $result = $data->toArray();
            }
            return $contll->msgOut(true, $result);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
