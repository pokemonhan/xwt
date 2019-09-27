<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\FrontendUsersPrivacyFlow;
use App\Models\User\FrontendUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class UserHandleDeactivateAction
 * @package App\Http\SingleActions\Backend\Users
 */
class UserHandleDeactivateAction
{
    /**
     * @var FrontendUser
     */
    protected $model;

    /**
     * @param FrontendUser $frontendUser FrontendUser.
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 用户冻结账号功能
     * @param  BackEndApiMainController $contll     BackEndApiMainController.
     * @param  array                    $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $userEloq = $this->model::find($inputDatas['user_id']);
        //用户类型你:1 直属  2 代理 3 会员
        $allId[] = $inputDatas['user_id'];
        $isFronzenChild = $inputDatas['is_frozen_child'] ?? 0;
        //是否冻结下级
        if ((int) $isFronzenChild === 1) {
            if ($userEloq->type === 2 || $userEloq->type === 1) {
                $allSon = $userEloq->children;
                $this->getAllChildren($allId, $allSon);
            }
        }
        if ($userEloq !== null) {
            DB::beginTransaction();
            try {
                $this->model::whereIn('id', $allId)->update(['frozen_type' => $inputDatas['frozen_type']]);
                $userAdmitFlowLog = new FrontendUsersPrivacyFlow();
                $data = [
                    'admin_id' => $contll->partnerAdmin->id,
                    'admin_name' => $contll->partnerAdmin->name,
                    'user_id' => $userEloq->id,
                    'username' => $userEloq->username,
                    'comment' => $inputDatas['comment'],
                ];
                $userAdmitFlowLog->fill($data);
                $userAdmitFlowLog->save();
                DB::commit();
                return $contll->msgOut(true);
            } catch (Exception $e) {
                DB::rollBack();
                return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * 获取所有下级
     * @param array  $allId  所有下级id.
     * @param object $allSon 所有一级.
     * @return void
     */
    public function getAllChildren(array &$allId, object $allSon)
    {
        foreach ($allSon as $sonItem) {
            $allId[] = $sonItem->id;
            if ($sonItem->type === 2) {
                $children = $sonItem->children;
                if ($children->isNotEmpty()) {
                    $this->getAllChildren($allId, $children);
                }
            }
        }
    }
}
