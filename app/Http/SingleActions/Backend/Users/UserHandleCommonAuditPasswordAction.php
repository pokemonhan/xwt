<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\Users\UserHandleController;
use App\Models\User\FrontendUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserHandleCommonAuditPasswordAction
{
    /**
     * @param  UserHandleController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(UserHandleController $contll, array $inputDatas): JsonResponse
    {
        $eloqM = $contll->modelWithNameSpace($contll->withNameSpace);
        $applyUserEloq = $eloqM::where([
            ['id', '=', $inputDatas['id']],
            ['type', '=', $inputDatas['type']],
            ['status', '=', 0],
        ])->first();
        if ($applyUserEloq !== null) {
            $auditFlowEloq = $applyUserEloq->auditFlow;
            //handle User
            $user = FrontendUser::find($applyUserEloq->user_id);
            if ($user === null) {
                return $contll->msgOut(false, [], '100111');
            }
            if ($applyUserEloq->type == 1) {
                $user->password = $applyUserEloq->audit_data;
            } else {
                $user->fund_password = $applyUserEloq->audit_data;
            }
            DB::beginTransaction();
            try {
                if ($inputDatas['status'] == 1) {
                    $user->save();
                }
                $auditFlowEloq->auditor_id = $contll->partnerAdmin->id;
                $auditFlowEloq->auditor_note = $inputDatas['auditor_note'];
                $auditFlowEloq->auditor_name = $contll->partnerAdmin->name;
                $auditFlowEloq->save();
                $applyUserEloq->status = $inputDatas['status'];
                $applyUserEloq->save();
                DB::commit();
                return $contll->msgOut(true);
            } catch (Exception $e) {
                DB::rollBack();
                return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
            }
        } else {
            return $contll->msgOut(false, [], '100102');
        }
    }
}
