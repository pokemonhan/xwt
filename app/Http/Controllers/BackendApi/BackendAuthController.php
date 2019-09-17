<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Requests\Backend\BackendAuthDeletePartnerAdminRequest;
use App\Http\Requests\Backend\BackendAuthRegisterRequest;
use App\Http\Requests\Backend\BackendAuthSelfResetPasswordRequest;
use App\Http\Requests\Backend\BackendAuthUpdatePAdmPasswordRequest;
use App\Http\Requests\Backend\BackendAuthSearchGroupRequest;
use App\Http\Requests\Backend\BackendAuthUpdateUserGroupRequest;
use App\Http\SingleActions\Backend\BackendAuthAllUserAction;
use App\Http\SingleActions\Backend\BackendAuthDeletePartnerAdminAction;
use App\Http\SingleActions\Common\Backend\BackendAuthLoginAction;
use App\Http\SingleActions\Common\Backend\BackendAuthLogoutAction;
use App\Http\SingleActions\Backend\BackendAuthRegisterAction;
use App\Http\SingleActions\Backend\BackendAuthSelfResetPasswordAction;
use App\Http\SingleActions\Backend\BackendAuthUpdatePAdmPasswordAction;
use App\Http\SingleActions\Backend\BackendAuthSearchUserAction;
use App\Http\SingleActions\Backend\BackendAuthSearchGroupAction;
use App\Http\SingleActions\Backend\BackendAuthUpdateUserGroupAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BackendAuthController extends BackEndApiMainController
{

    /**
     * Login user and create token
     *
     * @param  Request $request
     * @param  BackendAuthLoginAction $action
     * @return JsonResponse [string]   access_token
     */
    public function login(Request $request, BackendAuthLoginAction $action): JsonResponse
    {
        return $action->execute($this, $request);
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
     * change partner user Password
     * @param  BackendAuthSelfResetPasswordRequest $request
     * @param  BackendAuthSelfResetPasswordAction $action
     * @return JsonResponse
     */
    public function selfResetPassword(
        BackendAuthSelfResetPasswordRequest $request,
        BackendAuthSelfResetPasswordAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * Register api
     * @param  BackendAuthRegisterRequest $request
     * @param  BackendAuthRegisterAction $action
     * @return JsonResponse
     */
    public function register(BackendAuthRegisterRequest $request, BackendAuthRegisterAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取所有当前平台的商户管理员用户
     * @param  BackendAuthAllUserAction $action
     * @return JsonResponse
     */
    public function allUser(BackendAuthAllUserAction $action): ?JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 修改管理员的归属组
     * @param  BackendAuthUpdateUserGroupRequest $request
     * @param  BackendAuthUpdateUserGroupAction $action
     * @return JsonResponse
     */
    public function updateUserGroup(
        BackendAuthUpdateUserGroupRequest $request,
        BackendAuthUpdateUserGroupAction $action
    ): JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * details api
     *
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        $user = $this->partnerAdmin;
        return $this->msgOut(true, $user);
    }

    /**
     * Logout user (Revoke the token)
     * @param  Request $request
     * @param  BackendAuthLogoutAction $action
     * @return JsonResponse [string]    message
     */
    public function logout(Request $request, BackendAuthLogoutAction $action): JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * Get the authenticated User
     *
     * @param  Request $request
     * @return JsonResponse [json] user object
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * 删除管理员
     * @param  BackendAuthDeletePartnerAdminRequest $request
     * @param  BackendAuthDeletePartnerAdminAction $action
     * @return JsonResponse
     */
    public function deletePartnerAdmin(
        BackendAuthDeletePartnerAdminRequest $request,
        BackendAuthDeletePartnerAdminAction $action
    ): ?JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param  BackendAuthUpdatePAdmPasswordRequest $request
     * @param  BackendAuthUpdatePAdmPasswordAction $action
     * @return JsonResponse
     */
    public function updatePAdmPassword(
        BackendAuthUpdatePAdmPasswordRequest $request,
        BackendAuthUpdatePAdmPasswordAction $action
    ): ?JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * @param  BackendAuthSearchUserAction $action
     * @return JsonResponse
     */
    public function searchUser(
        BackendAuthSearchUserAction $action
    ): ?JsonResponse {
        return $action->execute($this);
    }

    /**
     * @param  BackendAuthSearchGroupRequest $request
     * @param  BackendAuthSearchGroupAction $action
     * @return JsonResponse
     */
    public function searchGroup(
        BackendAuthSearchGroupRequest $request,
        BackendAuthSearchGroupAction $action
    ): ?JsonResponse {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
