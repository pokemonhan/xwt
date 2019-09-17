<?php

namespace App\Http\Controllers\BackendApi\Admin;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupCreateRequest;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupDestroyRequest;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupEditRequest;
use App\Http\Requests\Backend\Admin\PartnerAdminGroupSpecificGroupUsersRequest;
use App\Http\SingleActions\Backend\Admin\PartnerAdminGroupAccessOnlyColumnAction;
use App\Http\SingleActions\Backend\Admin\PartnerAdminGroupCreateAction;
use App\Http\SingleActions\Backend\Admin\PartnerAdminGroupDestroyAction;
use App\Http\SingleActions\Backend\Admin\PartnerAdminGroupEditAction;
use App\Http\SingleActions\Backend\Admin\PartnerAdminGroupIndexAction;
use App\Http\SingleActions\Backend\Admin\PartnerAdminGroupSpecificGroupUsersAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerAdminGroupController extends BackEndApiMainController
{
    public $postUnaccess = ['id', 'updated_at', 'created_at']; //不需要接收的字段

    /**
     * Display a listing of the resource.
     *
     * @param  PartnerAdminGroupIndexAction  $action
     * @return JsonResponse
     */
    public function index(PartnerAdminGroupIndexAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  PartnerAdminGroupCreateRequest $request
     * @param  PartnerAdminGroupCreateAction  $action
     * @return JsonResponse
     */
    public function create(PartnerAdminGroupCreateRequest $request, PartnerAdminGroupCreateAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param  PartnerAdminGroupAccessOnlyColumnAction  $action
     * @return array
     */
    protected function accessOnlyColumn(PartnerAdminGroupAccessOnlyColumnAction $action): array
    {
        return $action->execute($this);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PartnerAdminGroupEditRequest $request
     * @param  PartnerAdminGroupEditAction  $action
     * @return JsonResponse
     */
    public function edit(PartnerAdminGroupEditRequest $request, PartnerAdminGroupEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除组管理员角色
     * @param  PartnerAdminGroupDestroyRequest $request
     * @param  PartnerAdminGroupDestroyAction  $action
     * @return JsonResponse
     */
    public function destroy(
        PartnerAdminGroupDestroyRequest $request,
        PartnerAdminGroupDestroyAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取管理员角色
     * @param  PartnerAdminGroupSpecificGroupUsersRequest $request
     * @param  PartnerAdminGroupSpecificGroupUsersAction  $action
     * @return JsonResponse
     */
    public function specificGroupUsers(
        PartnerAdminGroupSpecificGroupUsersRequest $request,
        PartnerAdminGroupSpecificGroupUsersAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
