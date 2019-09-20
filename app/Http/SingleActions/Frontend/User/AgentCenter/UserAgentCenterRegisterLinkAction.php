<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUsersRegisterableLink;
use Exception;
use Illuminate\Http\JsonResponse;

class UserAgentCenterRegisterLinkAction
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
     * 开户链接
     * @param  FrontendApiMainController $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $expire = $inputDatas['expire'];
        $channel = $inputDatas['channel'];
        $prize_group = $inputDatas['prize_group'];
        $userType = $inputDatas['user_type'];

        $userInfo = $contll->currentAuth->user();

        $verifyData = $this->verifyData($expire, $userInfo);
        if ($verifyData['success'] === false) {
            return $contll->msgOut(false, [], $verifyData['code']);
        }

        //开户链接
        $keyword = random_int(11, 99) . substr(uniqid(), 7);
        $registerUrl = '/register/' . $keyword;

        $addData = [
            'user_id' => $userInfo->id,
            'username' => $userInfo->username,
            'prize_group' => $prize_group,
            'type' => 0, //0链接注册1扫码注册
            'user_type' => $userType, //链接注册的用户类型：2代理  3用户
            'channel' => $channel,
            'keyword' => $keyword,
            'url' => $registerUrl,
            'status' => 1,
            'platform_id' => $contll->currentPlatformEloq->platform_id,
            'platform_sign' => $contll->currentPlatformEloq->platform_sign,
        ];

        if ($expire > 0) {
            $addData['valid_days'] = $expire;
            $expiredAt = strtotime('+ '.$expire.' days');
            if ($expiredAt !== false) {
                $addData['expired_at'] = date('Y-m-d H:i:s', $expiredAt);
            }
        }

        try {
            $FrontendUsersRegisterableLink = new FrontendUsersRegisterableLink();
            $FrontendUsersRegisterableLink->fill($addData);
            $FrontendUsersRegisterableLink->save();
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }

        return $contll->msgOut(true, $FrontendUsersRegisterableLink);
    }

    private function verifyData($expire, $userInfo)
    {
        //链接有效期列表
        $expire_list = configure('users_register_expire', '[0,1,7,30,90]');
        $expire_list = json_decode($expire_list, true);

        if (!in_array($expire, $expire_list)) {
            return $this->returnArr(false, '100600');
        }

        //平台最低奖金组
        $minPrizeGroup = configure('min_bet_prize_group');
        //平台最高奖金组
        $maxPrizeGroup = configure('max_bet_prize_group');

        if ($userInfo->prize_group < $maxPrizeGroup) {
            $maxPrizeGroup = $userInfo->prize_group;
        }

        if ($userInfo->prize_group < $minPrizeGroup || $userInfo->prize_group > $maxPrizeGroup) {
            return $this->returnArr(false, '100601');
        }

        if ($userInfo->type !== 2 && $userInfo->type !== 1) {
            return $this->returnArr(false, '100602');
        }

        //当前用户最多10条有效链接
        $userfulLinksCount = $this->model->where('status', 1)
            ->where('user_id', $userInfo->id)
            ->where(
                static function ($query) {
                    $query->whereNull('expired_at')->orWhere('expired_at', '>=', time());
                }
            )->count();

        if ($userfulLinksCount > 10) {
            return $this->returnArr(false, '100603');
        }
        return $this->returnArr(true);
    }

    private function returnArr($success = false, $code = '')
    {
        return ['success' => $success, 'code' => $code];
    }
}
