<?php

namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminAuditPasswordsList;
use App\Models\BackendAdminAuditFlowList;
use App\Models\User\FrontendUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserHandleCommonHandleUserPasswordAction
{
    /**
     * 申请资金密码跟密码共用功能
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @param  int $type
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas, int $type): JsonResponse
    {
        $applyUserEloq = FrontendUser::find($inputDatas['id']);
        if ($applyUserEloq !== null) {
            $auditFlowEloq = new BackendAdminAuditFlowList();
            $adminApplyEloq = new BackendAdminAuditPasswordsList();
            //###################
            $adminApplyCheck = $adminApplyEloq::where([
                ['user_id', '=', $applyUserEloq->id],
                ['status', '=', 0],
                ['type', '=', $type],
            ])->exists();
            if ($adminApplyCheck === true) {
                $code = '';
                if ($type === 1) {
                    $code = '100100';
                } else {
                    if ($type === 2) {
                        $code = '100101';
                    }
                }
                return $contll->msgOut(false, [], $code);
            }
            //###################
            $flowData = [
                'admin_id' => $contll->partnerAdmin->id,
                'admin_name' => $contll->partnerAdmin->name,
                'username' => $applyUserEloq->username,
                'apply_note' => $inputDatas['apply_note'] ?? '',
            ];
            DB::beginTransaction();
            try {
                $auditResult = $auditFlowEloq->fill($flowData);
                $auditResult->save();
                $auditData = [
                    'type' => $type,
                    'user_id' => $applyUserEloq->id,
                    'audit_data' => Hash::make($inputDatas['password']),
                    'audit_flow_id' => $auditResult->id,
                    'status' => 0,
                ];
                $adminApplyResult = $adminApplyEloq->fill($auditData);
                $adminApplyResult->save();
                DB::commit();
                return $contll->msgOut(true);
            } catch (Exception $e) {
                DB::rollBack();
                return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
            }
        } else {
            return $contll->msgOut(false, [], '100004');
        }
    }
}
