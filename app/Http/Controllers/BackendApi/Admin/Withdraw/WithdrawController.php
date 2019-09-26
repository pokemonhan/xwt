<?php

namespace App\Http\Controllers\BackendApi\Admin\Withdraw;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Withdraw\WithdrawChannelRequest;
use App\Http\Requests\Backend\Admin\Withdraw\WithdrawShowRequest;
use App\Http\Requests\Backend\Admin\Withdraw\WithdrawStatusRequest;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawChannelAction;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawShowAction;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawStatusAction;
use Illuminate\Http\JsonResponse;

/**
 * Class WithdrawController
 * @package App\Http\Controllers\BackendApi\Admin\Withdraw
 */
class WithdrawController extends BackEndApiMainController
{
    /**
     * @param WithdrawShowRequest $request 验证器.
     * @param WithdrawShowAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function show(WithdrawShowRequest $request, WithdrawShowAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param WithdrawStatusRequest $request 验证器.
     * @param WithdrawStatusAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function status(WithdrawStatusRequest $request, WithdrawStatusAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param WithdrawChannelRequest $request 验证器.
     * @param WithdrawChannelAction  $action  逻辑处理.
     * @return JsonResponse
     */
    public function getWithdrawChannel(WithdrawChannelRequest $request, WithdrawChannelAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
