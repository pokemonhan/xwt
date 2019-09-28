<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\Users\UserHandleController;
use Illuminate\Http\JsonResponse;
use App\Models\User\FrontendUser;
use Exception;

/**
 * 设置用户日工资比例
 */
class UserHandleSetDaysalaryPercentageAction
{
    /**
     * @param UserHandleController $contll     Controller.
     * @param array                $inputDatas 传递的参数.
     * @return JsonResponse
     */
    public function execute(UserHandleController $contll, array $inputDatas): JsonResponse
    {
        $userELoq = FrontendUser::find($inputDatas['user_id']);

        if ($userELoq === null) {
            return $contll->msgOut(false, [], '100111');
        }
        if (!in_array($userELoq->type, [FrontendUser::TYPE_TOP_AGENT, FrontendUser::TYPE_AGENT])) {
            return $contll->msgOut(false, [], '100124');
        }

        try {
            $userELoq->daysalary_percentage = $inputDatas['daysalary_percentage'];
            $userELoq->save();
            return $contll->msgOut(true);
        } catch (Exception $exception) {
            return $contll->msgOut(false, [], $exception->getCode(), $exception->getMessage());
        }
    }
}
