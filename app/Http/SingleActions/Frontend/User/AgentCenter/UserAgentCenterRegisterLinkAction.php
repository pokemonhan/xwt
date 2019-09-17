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
        $is_agent = $inputDatas['is_agent'];

        //链接有效期列表
        $expire_list = configure('users_register_expire', "[0,1,7,30,90]");
        $expire_list = json_decode($expire_list, true);

        if (!in_array($expire, $expire_list)) {
            return $contll->msgOut(false, [], '100600');
        }

        //最低开户奖金组
        $min_user_prize_group = configure('min_user_prize_group');
        //最高开户奖金组
        $max_user_prize_group = configure('max_user_prize_group');

        $userInfo = $contll->currentAuth->user();
        if ($userInfo->prize_group < $max_user_prize_group) {
            $max_user_prize_group = $userInfo->prize_group;
        }

        if ($prize_group < $min_user_prize_group || $prize_group > $max_user_prize_group) {
            return $contll->msgOut(false, [], '100601');
        }

        if ($userInfo->type != 2 && $userInfo->type != 1) {
            return $contll->msgOut(false, [], '100602');
        }

        //当前用户最多10条有效链接
        $userfulLinksCount = $this->model->where('status', 1)
            ->where('user_id', $userInfo->id)
            ->where(
                function ($query) {
                    $query->whereNull('expired_at')->orWhere('expired_at', '>=', time());
                }
            )->count();

        if ($userfulLinksCount > 10) {
            return $contll->msgOut(false, [], '100603');
        }

        //开户链接
        $keyword = random_int(11, 99) . substr(uniqid(), 7);
        $url = '/register/' . $keyword;

        $addData = [
            'user_id' => $userInfo->id,
            'username' => $userInfo->username,
            'prize_group' => $prize_group,
            'type' => 0, //0链接注册1扫码注册
            'is_agent' => $is_agent, //链接注册的用户类型：0用户1代理
            'channel' => $channel,
            'keyword' => $keyword,
            'url' => $url,
            'status' => 1,
            'platform_id' => $contll->currentPlatformEloq->platform_id,
            'platform_sign' => $contll->currentPlatformEloq->platform_sign,
        ];

        if ($expire > 0) {
            $addData['valid_days'] = $expire;
            $expiredAt = strtotime("+ {$expire} days");
            if ($expiredAt !== false) {
                $addData['expired_at'] = date('Y-m-d H:i:s', $expiredAt);
            }
        }

        try {
            $FrontendUsersRegisterableLink = new $this->model;
            $FrontendUsersRegisterableLink->fill($addData);
            $FrontendUsersRegisterableLink->save();
        } catch (Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }

        return $contll->msgOut(true, $FrontendUsersRegisterableLink);
    }
}
