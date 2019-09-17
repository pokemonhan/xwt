<?php

namespace App\Http\Controllers\MobileApi\User\Fund;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\User\Fund\UserBankCardAddRequest;
use App\Http\Requests\Frontend\User\Fund\UserBankCardDeleteRequest;
use App\Http\Requests\Frontend\User\Fund\UserBankCityListsRequest;
use App\Http\Requests\Frontend\User\Fund\UserBankCardTwoAddVerifiyRequest;
use App\Http\SingleActions\Frontend\User\Fund\UserBankBankListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardAddAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardDeleteAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCityListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankProvinceListsAction;
use App\Http\SingleActions\Frontend\User\Fund\UserBankCardTwoAddVerifiyAction;
use Illuminate\Http\JsonResponse;

class UserBankCardController extends FrontendApiMainController
{
    /**
     * 用户银行卡列表
     * @param  UserBankCardListsAction $action
     * @return JsonResponse
     */
    public function lists(UserBankCardListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 用户添加绑定银行卡
     * @param UserBankCardAddRequest $request
     * @param UserBankCardAddAction  $action
     * @return JsonResponse
     */
    public function addCard(UserBankCardAddRequest $request, UserBankCardAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户删除绑定银行卡
     * @param  UserBankCardDeleteRequest $request
     * @param  UserBankCardDeleteAction  $action
     * @return JsonResponse
     */
    public function delete(UserBankCardDeleteRequest $request, UserBankCardDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 添加银行卡时选择的银行列表
     * @param  UserBankBankListsAction $action
     * @return JsonResponse
     */
    public function bankLists(UserBankBankListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加银行卡时选择的省份列表
     * @param  UserBankProvinceListsAction $action
     * @return JsonResponse
     */
    public function provinceLists(UserBankProvinceListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加银行卡时选择的城市列表
     * @param  UserBankCityListsRequest $request
     * @param  UserBankCityListsAction  $action
     * @return JsonResponse
     */
    public function cityLists(UserBankCityListsRequest $request, UserBankCityListsAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 二次添加银行卡验证资金密码与开户姓名
     * @param UserBankCardTwoAddVerifiyRequest $request
     * @param UserBankCardTwoAddVerifiyAction $action
     * @return JsonResponse
     */
    public function twoAddVerifiy(UserBankCardTwoAddVerifiyRequest $request, UserBankCardTwoAddVerifiyAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
