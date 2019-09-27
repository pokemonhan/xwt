<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Requests\Frontend\FrontendAuthRegisterRequest;
use App\Http\Requests\Frontend\FrontendAuthResetFundPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthResetSpecificInfosRequest;
use App\Http\Requests\Frontend\FrontendAuthResetUserPasswordRequest;
use App\Http\Requests\Frontend\FrontendAuthSelfResetPasswordRequest;
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
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class FrontendAuthController
 * @package App\Http\Controllers\FrontendApi
 */
class FrontendAuthController extends FrontendApiMainController
{
    /**
     * @var string $eloqM
     */
    public $eloqM = 'User\FrontendUser';

    /**
     * Login user and create token
     * @param FrontendAuthLoginAction $action  验证登录.
     * @param Request                 $request 请求.
     * @return JsonResponse [string]  access_token
     */
    public function login(FrontendAuthLoginAction $action, Request $request): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 用户信息
     * @param FrontendAuthUserDetailAction $action 用户详情.
     * @return JsonResponse
     */
    public function userDetail(FrontendAuthUserDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * change partner user Password
     * @param FrontendAuthSelfResetPasswordRequest $request 改变用户密码请求.
     * @return JsonResponse
     */
    public function selfResetPassword(FrontendAuthSelfResetPasswordRequest $request)
    {
        $inputDatas = $request->validated();
        if (!Hash::check($inputDatas['old_password'], $this->partnerUser->password)) {
            return $this->msgOut(false, [], '100003');
        } else {
            $token = $this->refresh();
            $this->partnerUser->password = Hash::make($inputDatas['password']);
            $this->partnerUser->remember_token = $token;
            try {
                $this->partnerUser->save();
                $expireInMinute = $this->currentAuth->factory()->getTTL();
                $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
                $data = [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $expireAt,
                ];
                return $this->msgOut(true, $data);
            } catch (Exception $exception) {
                return $this->msgOut(false, [], $exception->getCode(), $exception->getMessage());
            }
        }
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->currentAuth->refresh();
    }

    /**
     * @param FrontendAuthRegisterRequest $request 用户注册请求.
     * @param FrontendAuthRegisterAction  $action  用户注册.
     * @return JsonResponse
     */
    public function register(FrontendAuthRegisterRequest $request, FrontendAuthRegisterAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        $inputDatas['host'] = $request->server('HTTP_HOST');
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param Request                  $request 请求.
     * @param FrontendAuthLogoutAction $action  用户退出.
     * @return JsonResponse
     */
    public function logout(Request $request, FrontendAuthLogoutAction $action): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * Get the authenticated User
     * @param Request $request 请求.
     * @return JsonResponse [json] user object
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * 用户修改登录密码
     * @param FrontendAuthResetUserPasswordRequest   $request 用户修改登录密码请求.
     * @param FrontendCommonHandleUserPasswordAction $action  用户修改登录操作.
     * @return JsonResponse
     */
    public function resetUserPassword(FrontendAuthResetUserPasswordRequest $request, FrontendCommonHandleUserPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas, 1);
    }

    /**
     * 用户修改资金密码
     * @param FrontendAuthResetFundPasswordRequest   $request 用户修改资金密码请求.
     * @param FrontendCommonHandleUserPasswordAction $action  用户修改资金密码.
     * @return JsonResponse
     */
    public function resetFundPassword(FrontendAuthResetFundPasswordRequest $request, FrontendCommonHandleUserPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas, 2);
    }

    /**
     * 用户设置资金密码
     * @param FrontendAuthSetFundPasswordRequest $request 用户设置资金密码请求.
     * @param FrontendAuthSetFundPasswordAction  $action  用户设置资金密码操作.
     * @return JsonResponse
     */
    public function setFundPassword(FrontendAuthSetFundPasswordRequest $request, FrontendAuthSetFundPasswordAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户个人信息
     * @param FrontendAuthUserSpecificInfosAction $action 用户个人信息.
     * @return JsonResponse
     */
    public function userSpecificInfos(FrontendAuthUserSpecificInfosAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 用户设置个人信息
     * @param FrontendAuthResetSpecificInfosRequest $request 用户设置个人信息请求.
     * @param FrontendAuthResetSpecificInfosAction  $action  用户设置个人信息操作.
     * @return JsonResponse
     */
    public function resetSpecificInfos(FrontendAuthResetSpecificInfosRequest $request, FrontendAuthResetSpecificInfosAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 用户头像列表
     * @param FrontendAuthUserAvatarsListAction $action 获取用户头像列表操作.
     * @return JsonResponse
     */
    public function userAvatarsList(FrontendAuthUserAvatarsListAction $action): JsonResponse
    {
        return $action->execute($this);
    }
}
