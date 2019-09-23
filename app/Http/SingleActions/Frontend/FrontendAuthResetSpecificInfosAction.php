<?php

namespace App\Http\SingleActions\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class FrontendAuthResetSpecificInfosAction
 * @package App\Http\SingleActions\Frontend
 */
class FrontendAuthResetSpecificInfosAction
{
    /**
     * 用户设置个人信息
     * @param FrontendApiMainController $contll     FrontendApiMainController.
     * @param array                     $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $specificinfoEloq = $contll->partnerUser->specific;
        $userEloq = $contll->partnerUser;
        if ($specificinfoEloq === null) {
            return $contll->msgOut(false, [], '100023');
        }
        DB::beginTransaction();
        try {
            $specificinfoEloq->nickname = $inputDatas['nickname'] ?? $specificinfoEloq->nickname;
            $specificinfoEloq->realname = $inputDatas['realname'] ?? $specificinfoEloq->realname;
            $specificinfoEloq->mobile = $inputDatas['mobile'] ?? $specificinfoEloq->mobile;
            $specificinfoEloq->email = $inputDatas['email'] ?? $specificinfoEloq->email;
            $specificinfoEloq->zip_code = $inputDatas['zip_code'] ?? $specificinfoEloq->zip_code;
            $specificinfoEloq->address = $inputDatas['address'] ?? $specificinfoEloq->address;
            $specificinfoEloq->save();
            $userEloq->pic_path = $inputDatas['pic_path'] ?? $userEloq->pic_path;
            $userEloq->save();
            DB::commit();
            return $contll->msgOut(true);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $contll->msgOut(false, [], $exception->getCode(), $exception->getMessage());
        }
    }
    /**
     * 生成用户个人信息
     * @param  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    // public function createSpecificInfo($contll, $inputDatas): JsonResponse
    // {
    //     DB::beginTransaction();
    //     try {
    //         $specificinfoEloq = new FrontendUsersSpecificInfo();
    //         $specificinfoEloq->fill($inputDatas);
    //         $specificinfoEloq->save();
    //         $userEloq = $contll->partnerUser;
    //         $userEloq->user_specific_id = $specificinfoEloq->id;
    //         $userEloq->save();
    //         DB::commit();
    //         return $contll->msgOut(true);
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         $errorObj = $e->getPrevious()->getPrevious();
    //         [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
    //         return $contll->msgOut(false, [], $sqlState, $msg);
    //     }
    // }
}
