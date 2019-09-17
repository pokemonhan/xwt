<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class PartnerAdminGroupDestroyAction
{
    protected $model;

    /**
     * @param  BackendAdminAccessGroup  $backendAdminAccessGroup
     */
    public function __construct(BackendAdminAccessGroup $backendAdminAccessGroup)
    {
        $this->model = $backendAdminAccessGroup;
    }

    /**
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $id = $inputDatas['id'];
        $datas = $this->model::where([
            ['id', '=', $id],
            ['group_name', '=', $inputDatas['group_name']],
        ])->first();
        if ($datas === null) {
            return $contll->msgOut(false, [], '100201');
        }
        try {
            if ($datas->adminUsers()->exists()) {
                $datas->adminUsers()->delete();
            }
            $datas->delete();
            //检查是否有人工充值权限
            $role = $datas->role == '*' ? Arr::wrap($datas->role) : Arr::wrap(json_decode($datas->role, true));
            $fundOperation = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first();
            if ($fundOperation !== null) {
                $isManualRecharge = in_array($fundOperation['id'], $role, true);
                //如果有有人工充值权限   删除  FundOperation  BackendAdminRechargePermitGroup 表
                if ($isManualRecharge === true) {
                    $fundOperationGroup = new BackendAdminRechargePermitGroup();
                    $fundOperationGroup->where('group_id', $id)->delete();
                    //需要删除的资金表 admin
                    $fundOperationEloq = new BackendAdminRechargePocessAmount();
                    $adminsData = BackendAdminUser::select('id')->where('group_id', $id)->get();
                    $admins = array_column($adminsData->toArray(), 'id');
                    if ($adminsData !== null) {
                        $fundOperationEloq->whereIn('admin_id', $admins)->delete();
                    }
                }
            }
            return $contll->msgOut(true);
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
