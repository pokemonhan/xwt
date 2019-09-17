<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUsersRegisterableLink;
use Illuminate\Http\JsonResponse;

class UserAgentCenterLinkDelAction
{
    protected $model;

    /**
     * RegisterLinkAction constructor.
     * @param FrontendUsersRegisterableLink $FrontendUsersRegisterableLink
     */
    public function __construct(FrontendUsersRegisterableLink $FrontendUsersRegisterableLink)
    {
        $this->model = $FrontendUsersRegisterableLink;
    }

    /**
     * 刪除鏈接
     * @param  FrontendApiMainController $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $id = $inputDatas['id'];

        $userInfo = $contll->currentAuth->user();
        $userId = $userInfo->id;

        $this->model->where('id', $id)->where('user_id', $userId)->update(['status' => 0]);

        return $contll->msgOut(true, []);
    }
}
