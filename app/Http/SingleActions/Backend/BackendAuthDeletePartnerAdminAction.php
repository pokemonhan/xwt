<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class BackendAuthDeletePartnerAdminAction
 * @package App\Http\SingleActions\Backend
 */
class BackendAuthDeletePartnerAdminAction
{
    /**
     * @var BackendAdminUser
     */
    protected $model;

    /**
     * @param BackendAdminUser $backendAdminUser BackendAdminUser.
     */
    public function __construct(BackendAdminUser $backendAdminUser)
    {
        $this->model = $backendAdminUser;
    }

    /**
     * 删除管理员
     * @param BackEndApiMainController $contll     BackEndApiMainController.
     * @param array                    $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $targetUserEloq = $this->model::where([
            ['id', '=', $inputDatas['id']],
            ['name', '=', $inputDatas['name']],
        ])->first();
        if ($targetUserEloq !== null) {
            //判断当前用户是否已登录
            if ($contll->partnerAdmin->id === $targetUserEloq->id && $contll->partnerAdmin->name === $targetUserEloq->name) {
                return $contll->msgOut(false, [], '100006');
            }
            if ($targetUserEloq->remember_token !== null) {
                try {
                    JWTAuth::setToken($targetUserEloq->remember_token);
                    JWTAuth::invalidate();
                } catch (JWTException $e) {
                    Log::info($e->getMessage());
                }
            }
            try {
                $targetUserEloq->delete();
                return $contll->msgOut(true);
            } catch (Exception $e) {
                return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
            }
        } else {
            return $contll->msgOut(false, [], '100004');
        }
    }
}
