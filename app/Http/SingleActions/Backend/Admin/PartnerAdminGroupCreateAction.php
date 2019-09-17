<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PartnerAdminGroupCreateAction
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
     * Show the form for creating a new resource.
     * @param  BackEndApiMainController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data['platform_id'] = $contll->currentPlatformEloq->platform_id;
            $data['group_name'] = $inputDatas['group_name'];
            $data['role'] = $inputDatas['role'];
            $role = $inputDatas['role'] == '*' ?
            Arr::wrap($inputDatas['role']) : Arr::wrap(json_decode($inputDatas['role'], true));
            $objPartnerAdminGroup = new $this->model;
            $objPartnerAdminGroup->fill($data);
            $objPartnerAdminGroup->save();
            //检查是否有人工充值权限
            $fundOperationCriteriaEloq = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first();
            if ($fundOperationCriteriaEloq !== null) {
                $isManualRecharge = in_array($fundOperationCriteriaEloq['id'], $role);
                //如果有人工充值权限   添加 backend_admin_recharge_permit_groups 表
                if ($isManualRecharge === true) {
                    $fundOperationGroup = new BackendAdminRechargePermitGroup();
                    $fundOperationData = [
                        'group_id' => $objPartnerAdminGroup->id,
                        'group_name' => $objPartnerAdminGroup->group_name,
                    ];
                    $fundOperationGroup->fill($fundOperationData);
                    $fundOperationGroup->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
        $partnerMenuObj = new BackendSystemMenu();
        $partnerMenuObj->createMenuDatas($objPartnerAdminGroup->id, $role);
        return $contll->msgOut(true, $data);
    }
}
