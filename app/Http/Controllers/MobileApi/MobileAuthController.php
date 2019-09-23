<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\FrontendAuthRegisterRequest;
use App\Http\Requests\Frontend\FrontendAuthResetFundPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthResetSpecificInfosRequest;
use App\Http\Requests\Frontend\FrontendAuthResetUserPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthSetFundPasswordRequest;
use App\Http\SingleActions\Common\Frontend\FrontendAuthLoginAction;
use App\Http\SingleActions\Common\Frontend\FrontendAuthLogoutAction;
use App\Http\SingleActions\Frontend\FrontendAuthRegisterAction;
use App\Http\SingleActions\Frontend\FrontendAuthResetSpecificInfosAction;
use App\Http\SingleActions\Frontend\FrontendAuthSetFundPasswordAction;
use App\Http\SingleActions\Frontend\FrontendAuthUserDetailAction;
use App\Http\SingleActions\Frontend\FrontendAuthUserSpecificInfosAction;
use App\Http\SingleActions\Frontend\FrontendCommonHandleUserPasswordAction;
use App\Http\SingleActions\Frontend\FrontendAuthUserAvatarsListAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class MobileAuthController
 * @package App\Http\Controllers\MobileApi
 */
class MobileAuthController extends FrontendApiMainController
{
    /**
     * @var string $eloqM
     */
    public $eloqM = 'User\FrontendUser';

    /**
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Login user and create token
     *
     * @param FrontendAuthLoginAction $action  FrontendAuthLoginAction.
     * @param Request                 $request Request.
     * @return JsonResponse
     */
    public function login(FrontendAuthLoginAction $action, Request $request): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * @param FrontendAuthRegisterRequest $request 注册请求.
     * @param FrontendAuthRegisterAction  $action  注册操作.
     * @return JsonResponse
     */
    public function register(FrontendAuthRegisterRequest $request, FrontendAuthRegisterAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        $inputDatas['host'] = $request->server('HTTP_HOST');
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户信息
     * @param FrontendAuthUserDetailAction $action 用户信息操作.
     * @return JsonResponse
     */
    public function userDetail(FrontendAuthUserDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        $token = $this->currentAuth->refresh();
        return $token;
    }

    /**
     * Logout user (Revoke the token)
     * @param Request                  $request Request.
     * @param FrontendAuthLogoutAction $action  用户退出操作.
     * @return JsonResponse [string] message
     */
    public function logout(Request $request, FrontendAuthLogoutAction $action): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request Request.
     * @return JsonResponse [json] user object
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * 用户修改登录密码
     * @param FrontendAuthResetUserPasswordRequest   $request 修改密码请求.
     * @param FrontendCommonHandleUserPasswordAction $action  修改密码操作.
     * @return JsonResponse
     */
    public function resetUserPassword(FrontendAuthResetUserPasswordRequest $request, FrontendCommonHandleUserPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas, 1);
    }

    /**
     * 用户修改资金密码
     * @param FrontendAuthResetFundPasswordRequest   $request 修改资金密码请求.
     * @param FrontendCommonHandleUserPasswordAction $action  修改资金密码操作.
     * @return JsonResponse
     */
    public function resetFundPassword(FrontendAuthResetFundPasswordRequest $request, FrontendCommonHandleUserPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas, 2);
    }

    /**
     * 用户设置资金密码
     * @param FrontendAuthSetFundPasswordRequest $request 设置资金密码请求.
     * @param FrontendAuthSetFundPasswordAction  $action  设置资金密码操作.
     * @return JsonResponse
     */
    public function setFundPassword(FrontendAuthSetFundPasswordRequest $request, FrontendAuthSetFundPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
    /**
     * 用户个人信息
     * @param FrontendAuthUserSpecificInfosAction $action 获取用户详细信息操作.
     * @return JsonResponse
     */
    public function userSpecificInfos(FrontendAuthUserSpecificInfosAction $action): JsonResponse
    {
        return $action->execute($this);
    }
    /**
     * 用户设置个人信息
     * @param  FrontendAuthResetSpecificInfosRequest $request 设置详细信息请求.
     * @param  FrontendAuthResetSpecificInfosAction  $action  设置详细信息操作.
     * @return JsonResponse
     */
    public function resetSpecificInfos(FrontendAuthResetSpecificInfosRequest $request, FrontendAuthResetSpecificInfosAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户头像列表
     * @param FrontendAuthUserAvatarsListAction $action 获取所有头像列表.
     * @return JsonResponse
     */
    public function userAvatarsList(FrontendAuthUserAvatarsListAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
