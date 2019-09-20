<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminAccessGroup;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Fund\BackendAdminRechargePermitGroup;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class BackendAuthRegisterAction
{
    /**
     * Register api
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $group = BackendAdminAccessGroup::find($inputDatas['group_id']);
        $role = $group->role === '*' ? Arr::wrap($group->role) : Arr::wrap(json_decode($group->role, true));
        $input = $inputDatas;
        $input['password'] = bcrypt($input['password']);
        $input['platform_id'] = $contll->currentPlatformEloq->platform_id;
        $user = BackendAdminUser::create($input);
        $fundOperationEloq = new BackendAdminRechargePocessAmount();
        if ($group->role === '*') {
            $permitGroup = BackendAdminRechargePermitGroup::where('group_id', $inputDatas['group_id'])->first();
            //判断该组是否存在
            if ($permitGroup === null) {
                $fundOperationGuoupEloq = new BackendAdminRechargePermitGroup();
                $insertPermitGroup = ['group_id' => $group->id, 'group_name' => $group->group_name];
                $fundOperationGuoupEloq->fill($insertPermitGroup);
                $fundOperationGuoupEloq->save();
            }
            $insertData = ['admin_id' => $user->id];
            $fundOperationEloq->fill($insertData);
            $fundOperationEloq->save();
        } else {
            $fundOperation = BackendSystemMenu::select('id')->where('route', '/manage/recharge')->first();
            if ($fundOperation !== null) {
                $isManualRecharge = in_array($fundOperation['id'], $role, true);
                if ($isManualRecharge === true) {
                    $insertData = ['admin_id' => $user->id];
                    $fundOperationEloq->fill($insertData);
                    $fundOperationEloq->save();
                }
            }
        }
        $credentials = request(['email', 'password']);
        $token = $contll->currentAuth->attempt($credentials);
        $success['token'] = $token;
        $success['name'] = $user->name;
        return $contll->msgOut(true, $success);
    }
}
