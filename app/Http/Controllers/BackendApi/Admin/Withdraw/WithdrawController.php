<?php

namespace App\Http\Controllers\BackendApi\Admin\Withdraw;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Withdraw\WithdrawShowRequest;
use App\Http\Requests\Backend\Admin\Withdraw\WithdrawStatusRequest;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawShowAction;
use App\Http\SingleActions\Backend\Admin\Withdraw\WithdrawStatusAction;
use Illuminate\Http\JsonResponse;

class WithdrawController extends BackEndApiMainController
{
    public function show(WithdrawShowRequest $request, WithdrawShowAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    public function status(WithdrawStatusRequest $request, WithdrawStatusAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
