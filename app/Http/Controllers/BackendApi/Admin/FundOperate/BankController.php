<?php

namespace App\Http\Controllers\BackendApi\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\FundOperate\BankAddBankRequest;
use App\Http\Requests\Backend\Admin\FundOperate\BankDeleteBankRequest;
use App\Http\Requests\Backend\Admin\FundOperate\BankEditBankRequest;
use App\Http\SingleActions\Backend\Admin\FundOperate\BankAddAction;
use App\Http\SingleActions\Backend\Admin\FundOperate\BankDeleteAction;
use App\Http\SingleActions\Backend\Admin\FundOperate\BankDetailAction;
use App\Http\SingleActions\Backend\Admin\FundOperate\BankEditAction;
use Illuminate\Http\JsonResponse;

class BankController extends BackEndApiMainController
{

    /**
     * 银行列表
     * @param  BankDetailAction $action
     * @return JsonResponse
     */
    public function detail(BankDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加银行
     * @param  BankAddBankRequest $request
     * @param  BankAddAction $action
     * @return JsonResponse
     */
    public function add(BankAddBankRequest $request, BankAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑银行
     * @param  BankEditBankRequest $request
     * @param  BankEditAction $action
     * @return JsonResponse
     */
    public function edit(BankEditBankRequest $request, BankEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除银行
     * @param  BankDeleteBankRequest $request
     * @param  BankDeleteAction $action
     * @return JsonResponse
     */
    public function delete(BankDeleteBankRequest $request, BankDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
