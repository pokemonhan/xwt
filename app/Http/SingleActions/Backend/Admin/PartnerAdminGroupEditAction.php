<?php

namespace App\Http\SingleActions\Backend\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PartnerAdminGroupEditAction
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
        $datas = $this->model::find($id);
        $role = $inputDatas['role'] == '*' ?
            Arr::wrap($inputDatas['role']) : Arr::wrap(json_decode($inputDatas['role'], true));
        if ($datas !== null) {
            DB::beginTransaction();
            $datas->group_name = $inputDatas['group_name'];
            $datas->role = $inputDatas['role'];
            //#####################################################################################
            try {
                $datas->save();
                //检查提交的权限中 是否有 人工充值权限  $isManualRecharge
                $fundOperationCriteriaEloq = BackendSystemMenu::select('id')
                    ->where('route', '/manage/recharge')->first();
                $isManualRecharge = false;
                if ($fundOperationCriteriaEloq !== null) {
                    $isManualRecharge = in_array($fundOperationCriteriaEloq->id, $role, true);
                }
                //检查资金操作权限表是 否已存在 在当前用户组  $check
                $fundOperatinEloq = BackendAdminRechargePermitGroup::where('group_id', $datas->id)->first();
                $fundOperation = new BackendAdminRechargePocessAmount();
                $fundOperationDatas = [];
                if ($isManualRecharge === true) {
                    //如果之前没有就需要添加到 backend_admin_recharge_pocess_amounts 表里面 如果之前有表示已添加
                    if ($fundOperatinEloq === null) {
                        $fundOperationData = [
                            'group_id' => $datas->id,
                            'group_name' => $datas->group_name,
                        ];
                        $fundOperationGroup = new BackendAdminRechargePermitGroup();
                        $fundOperationGroup->fill($fundOperationData);
                        $fundOperationGroup->save();
                        //要添加到 fundoperation 里面的 当前 资金权限的id  有的 管理员 取出来
                        if ($fundOperationGroup->admins()->exists()) {
                            $partnerAdminsEloq = $fundOperationGroup->admins;
                            foreach ($partnerAdminsEloq as $adminData) {
                                $fundOperationData = [
                                    'admin_id' => $adminData->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ];
                                $fundOperationDatas[] = $fundOperationData;
                            }
                            $fundOperation->insert($fundOperationDatas);
                        }
                    }
                } else {
                    // 提交的时候是没有资金操作权限 然后
                    //之前有资金操作权限 所以 需要从 fundoperation 表里面删除
                    if ($fundOperatinEloq !== null) {
                        if ($fundOperatinEloq->admins()->exists()) {
                            $partnerAdminsEloq = $fundOperatinEloq->admins;
                            $adminsData = $partnerAdminsEloq->toArray();
                            $partnerAdminsIdArr = array_column($adminsData, 'id');
                            $fundOperation->whereIn('admin_id', $partnerAdminsIdArr)->delete();
                        }
                        BackendAdminRechargePermitGroup::where('group_id', $datas->id)->delete();
                    }
                }
                //更新管理员组菜单缓存
                $this->updateGroupMenu($datas);
                DB::commit();
                return $contll->msgOut(true, $datas->toArray());
            } catch (Exception $e) {
                DB::rollBack();
                return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
            }
        } else {
            return $contll->msgOut(false, [], '100200');
        }
    }

    //更新管理员组菜单缓存
    public function updateGroupMenu($accessGroupEloq): void
    {
        $role = json_decode($accessGroupEloq->role); //[1,2,3,4,5]
        $backendSystemMenuEloq = new BackendSystemMenu();
        $backendSystemMenuEloq->createMenuDatas($accessGroupEloq->id, $role);
    }
}
