<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendLinksRegisteredUsers;
use App\Models\User\FrontendUsersRegisterableLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserAgentCenterRegisterableLinkAction
{
    protected $model;

    /**
     * RegisterableLinkAction constructor.
     * @param FrontendUsersRegisterableLink $FrontendUsersRegisterableLink
     */
    public function __construct(FrontendUsersRegisterableLink $FrontendUsersRegisterableLink)
    {
        $this->model = $FrontendUsersRegisterableLink;
    }

    /**
     * 开户链接
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $data = [];

        $count = $contll->inputs['page_size'] ?? 20;

        //链接有效期列表
        $data['expire_list'] = configure('users_register_expire');
        //最低开户奖金组
        $data['min_user_prize_group'] = configure('min_bet_prize_group');
        //最高开户奖金组
        $data['max_user_prize_group'] = configure('max_bet_prize_group');

        $userInfo = $contll->currentAuth->user();
        if ($userInfo->prize_group < $data['max_user_prize_group']) {
            $data['max_user_prize_group'] = $userInfo->prize_group;
        }

        //有效开户链接
        $links = $this->model->where('status', 1)
            ->where('user_id', $userInfo->id)
            ->where(
                static function ($query) {
                    $query->whereNull('expired_at')->orWhere('expired_at', '>=', time());
                }
            )
            ->select('id', 'prize_group', 'valid_days', 'is_agent', 'channel', 'created_count', 'url', 'created_at')
            ->orderBy('expired_at', 'desc')
            ->paginate($count)->toArray();

        $linkIds = [];
        $linkCounts = [];
        $link = [];
        if ($links['total'] > 0) {
            foreach ($links['data'] as $link) {
                $linkIds[] = $link['id'];
            }

            $registeredUsers = FrontendLinksRegisteredUsers::whereIn('register_link_id', $linkIds)
                ->select('register_link_id', DB::raw('count(register_link_id) as count'))
                ->groupBy('register_link_id')
                ->get()
                ->toArray();

            foreach ($registeredUsers as $lc) {
                $linkCounts[$lc['register_link_id']] = $lc;
            }
        }

        foreach ($links['data'] as &$value) {
            if (isset($linkCounts[$value['id']])) {
                $value['register_count'] = $link['id'];
            } else {
                $value['register_count'] = 0;
            }
        }

        $data['links'] = $links;

        return $contll->msgOut(true, $data);
    }
}
