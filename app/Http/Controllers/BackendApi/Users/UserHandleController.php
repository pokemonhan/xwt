<?php

namespace App\Http\Controllers\BackendApi\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\UserHandleApplyResetUserFundPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleApplyResetUserPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleBankCardListRequest;
use App\Http\Requests\Backend\Users\UserHandleCommonAuditPasswordRequest;
use App\Http\Requests\Backend\Users\UserHandleCreateUserRequest;
use App\Http\Requests\Backend\Users\UserHandleDeactivateDetailRequest;
use App\Http\Requests\Backend\Users\UserHandleDeactivateRequest;
use App\Http\Requests\Backend\Users\UserHandleDeductionBalanceRequest;
use App\Http\Requests\Backend\Users\UserHandleSetUserAvatarRequest;
use App\Http\Requests\Backend\Users\UserHandleUserAccountChangeRequest;
use App\Http\Requests\Backend\Users\UserHandleUserRechargeHistoryRequest;
use App\Http\Requests\Backend\Users\UserHandleDeleteBankCardRequest;
use App\Http\SingleActions\Backend\Users\UserHandleBankCardListAction;
use App\Http\SingleActions\Backend\Users\UserHandleCommonAppliedPasswordHandleAction;
use App\Http\SingleActions\Backend\Users\UserHandleCommonAuditPasswordAction;
use App\Http\SingleActions\Backend\Users\UserHandleCommonHandleUserPasswordAction;
use App\Http\SingleActions\Backend\Users\UserHandleCreateUserAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeactivateAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeactivateDetailAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeductionBalanceAction;
use App\Http\SingleActions\Backend\Users\UserHandlePublicAvatarAction;
use App\Http\SingleActions\Backend\Users\UserHandleSetUserAvatarAction;
use App\Http\SingleActions\Backend\Users\UserHandleUserAccountChangeAction;
use App\Http\SingleActions\Backend\Users\UserHandleUserRechargeHistoryAction;
use App\Http\SingleActions\Backend\Users\UserHandleUsersInfoAction;
use App\Http\SingleActions\Backend\Users\UserHandleDeleteBankCardAction;
use App\Http\SingleActions\Backend\Users\UserHandleTotalProxyListAction;
use Illuminate\Http\JsonResponse;

/**
 * Class UserHandleController
 * @package App\Http\Controllers\BackendApi\Users
 */
class UserHandleController extends BackEndApiMainController
{
    /**
     * @var string $withNameSpace
     */
    public $withNameSpace = 'Admin\BackendAdminAuditPasswordsList';

    /**
     * 创建总代时获取当前平台的奖金组
     * @return JsonResponse
     */
    public function getUserPrizeGroup(): JsonResponse
    {
        $data['min'] = $this->minClassicPrizeGroup; //最低奖金组
        $data['max'] = $this->maxClassicPrizeGroup; //最高奖金组
        return $this->msgOut(true, $data);
    }

    /**
     * 创建总代与用户后台管理员操作创建
     * @param UserHandleCreateUserRequest $request 请求.
     * @param UserHandleCreateUserAction  $action  操作.
     * @return JsonResponse
     */
    public function createUser(UserHandleCreateUserRequest $request, UserHandleCreateUserAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户管理的所有用户信息表
     * @param UserHandleUsersInfoAction $action 操作.
     * @return JsonResponse
     */
    public function usersInfo(UserHandleUsersInfoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 申请用户密码功能
     * @param UserHandleApplyResetUserPasswordRequest  $request 请求.
     * @param UserHandleCommonHandleUserPasswordAction $action  操作.
     * @return JsonResponse
     */
    public function applyResetUserPassword(
        UserHandleApplyResetUserPasswordRequest $request,
        UserHandleCommonHandleUserPasswordAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, $action, 1);
    }

    /**
     * @param UserHandleApplyResetUserFundPasswordRequest $request 请求.
     * @param UserHandleCommonHandleUserPasswordAction    $action  操作.
     * @return JsonResponse
     */
    public function applyResetUserFundPassword(
        UserHandleApplyResetUserFundPasswordRequest $request,
        UserHandleCommonHandleUserPasswordAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $this->commonHandleUserPassword($inputDatas, $action, 2);
    }

    /**
     * 申请资金密码跟密码共用功能
     * @param array                                    $inputDatas 请求数据.
     * @param UserHandleCommonHandleUserPasswordAction $action     操作.
     * @param integer                                  $type       Todo if type new added then should notice on error message.
     * @return JsonResponse
     */
    public function commonHandleUserPassword(array $inputDatas, UserHandleCommonHandleUserPasswordAction $action, int $type): JsonResponse
    {
        return $action->execute($this, $inputDatas, $type);
    }

    /**
     * @param UserHandleCommonAppliedPasswordHandleAction $action 操作.
     * @return JsonResponse
     */
    public function appliedResetUserPasswordLists(UserHandleCommonAppliedPasswordHandleAction $action): JsonResponse
    {
        return $this->commonAppliedPasswordHandle($action);
    }

    /**
     * 用户资金密码已申请列表
     * @param UserHandleCommonAppliedPasswordHandleAction $action 操作.
     * @return JsonResponse
     */
    public function appliedResetUserFundPasswordLists(UserHandleCommonAppliedPasswordHandleAction $action): JsonResponse
    {
        return $this->commonAppliedPasswordHandle($action);
    }

    /**
     * 用户登录密码和资金密码公用列表
     * @param UserHandleCommonAppliedPasswordHandleAction $action 操作.
     * @return JsonResponse
     */
    private function commonAppliedPasswordHandle(UserHandleCommonAppliedPasswordHandleAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * @param  UserHandleCommonAuditPasswordRequest $request 请求.
     * @param  UserHandleCommonAuditPasswordAction  $action  操作.
     * @return JsonResponse
     */
    public function auditApplyUserPassword(
        UserHandleCommonAuditPasswordRequest $request,
        UserHandleCommonAuditPasswordAction $action
    ): JsonResponse {
        return $this->commonAuditPassword($request, $action);
    }

    /**
     * @param  UserHandleCommonAuditPasswordRequest $request 请求.
     * @param  UserHandleCommonAuditPasswordAction  $action  操作.
     * @return JsonResponse
     */
    public function auditApplyUserFundPassword(
        UserHandleCommonAuditPasswordRequest $request,
        UserHandleCommonAuditPasswordAction $action
    ): JsonResponse {
        return $this->commonAuditPassword($request, $action);
    }

    /**
     * @param  UserHandleCommonAuditPasswordRequest $request 请求.
     * @param  UserHandleCommonAuditPasswordAction  $action  操作.
     * @return JsonResponse
     */
    public function commonAuditPassword(UserHandleCommonAuditPasswordRequest $request, UserHandleCommonAuditPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户冻结账号功能
     * @param  UserHandleDeactivateRequest $request 请求.
     * @param  UserHandleDeactivateAction  $action  操作.
     * @return JsonResponse
     */
    public function deactivate(UserHandleDeactivateRequest $request, UserHandleDeactivateAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户冻结记录
     * @param  UserHandleDeactivateDetailRequest $request 请求.
     * @param  UserHandleDeactivateDetailAction  $action  操作.
     * @return JsonResponse
     */
    public function deactivateDetail(
        UserHandleDeactivateDetailRequest $request,
        UserHandleDeactivateDetailAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户帐变记录
     * @param  UserHandleUserAccountChangeRequest $request 请求.
     * @param  UserHandleUserAccountChangeAction  $action  操作.
     * @return JsonResponse
     */
    public function userAccountChange(
        UserHandleUserAccountChangeRequest $request,
        UserHandleUserAccountChangeAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户充值记录
     * @param  UserHandleUserRechargeHistoryRequest $request 请求.
     * @param  UserHandleUserRechargeHistoryAction  $action  操作.
     * @return JsonResponse
     */
    public function userRechargeHistory(
        UserHandleUserRechargeHistoryRequest $request,
        UserHandleUserRechargeHistoryAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 人工扣除用户资金
     * @param  UserHandleDeductionBalanceRequest $request 请求.
     * @param  UserHandleDeductionBalanceAction  $action  操作.
     * @return JsonResponse
     */
    public function deductionBalance(
        UserHandleDeductionBalanceRequest $request,
        UserHandleDeductionBalanceAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户银行卡列表
     * @param  UserHandleBankCardListRequest $request 请求.
     * @param  UserHandleBankCardListAction  $action  操作.
     * @return JsonResponse
     */
    public function bankCardList(
        UserHandleBankCardListRequest $request,
        UserHandleBankCardListAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param UserHandleDeleteBankCardRequest $request 请求.
     * @param UserHandleDeleteBankCardAction  $action  操作.
     * @return JsonResponse
     */
    public function deleteBankCard(
        UserHandleDeleteBankCardRequest $request,
        UserHandleDeleteBankCardAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取系统公共头像列表
     * @param  UserHandlePublicAvatarAction $action 操作.
     * @return JsonResponse
     */
    public function publicAvatar(UserHandlePublicAvatarAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 设定用户头像
     * @param  UserHandleSetUserAvatarRequest $request 请求.
     * @param  UserHandleSetUserAvatarAction  $action  操作.
     * @return JsonResponse
     */
    public function setUserAvatar(
        UserHandleSetUserAvatarRequest $request,
        UserHandleSetUserAvatarAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 总代、代理列表
     * @param UserHandleTotalProxyListAction $action 操作.
     * @return JsonResponse
     */
    public function TotalProxyList(UserHandleTotalProxyListAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
