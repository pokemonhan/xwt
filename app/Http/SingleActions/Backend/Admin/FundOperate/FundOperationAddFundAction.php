<?php

namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\BackendApi\Admin\FundOperate\FundOperationController;
use App\Lib\Common\FundOperation;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\User\Fund\BackendAdminRechargehumanLog;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FundOperationAddFundAction
{
    /**
     * 给管理员添加人工充值额度
     * @param  FundOperationController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FundOperationController $contll, array $inputDatas): JsonResponse
    {
        $fund = (float) $inputDatas['fund'];
        $adminDataEloq = BackendAdminUser::find($inputDatas['id']);
        $fundOperationAdmin = BackendAdminRechargePocessAmount::where('admin_id', $inputDatas['id'])->first();
        if (is_null($fundOperationAdmin)) {
            return $contll->msgOut(false, [], '101300');
        }
        //查看该管理员今日 手动添加的充值额度
        $fundOperationObj = new FundOperation();
        $checkFund = $fundOperationObj->checkRechargeToday($inputDatas['id'], $fund);
        if ($checkFund['success'] === false) {
            return $contll->msgOut(false, [], '', $checkFund['msg']);
        }
        DB::beginTransaction();
        try {
            $newFund = $fundOperationAdmin->fund + $fund;
            $adminEditData = ['fund' => $newFund];
            $fundOperationAdmin->fill($adminEditData);
            $fundOperationAdmin->save();
            $type = BackendAdminRechargehumanLog::SUPERADMIN;
            $in_out = BackendAdminRechargehumanLog::INCREMENT;
            $rechargeLog = new BackendAdminRechargehumanLog();
            $comment = '[人工充值额度操作]==>+' . $fund . '|[目前额度]==>' . $newFund;
            $fundOperationObj->insertOperationDatas(
                $rechargeLog,
                $type,
                $in_out,
                $contll->partnerAdmin->id,
                $contll->partnerAdmin->name,
                $adminDataEloq->id,
                $adminDataEloq->name,
                $fund,
                $comment,
                null
            );
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
