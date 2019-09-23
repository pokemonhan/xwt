<?php

namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\User\FrontendUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

/**
 * 团队管理
 */
class UserAgentCenterTeamManagementAction
{
    /**
     * [Model]
     * @var FrontendUser
     */
    protected $model;

    /**
     * @param FrontendUser $frontendUser FrontendUser.
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 团队管理
     * @param  FrontendApiMainController $contll     Controller.
     * @param  array                     $inputDatas 传递的参数.
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, array $inputDatas): JsonResponse
    {
        $pageSize = (int) ($inputDatas['page_size'] ?? 15);

        $where = $this->getWhere($contll, $inputDatas);

        $team = FrontendUser::select(
            'id',
            'username',
            'prize_group',
            'created_at as register_at ',
            'last_login_time',
        )
            ->where($where)
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize)
            ->makeHidden(['account', 'specific']);
        return $contll->msgOut(true, $team);
    }

    /**
     * 获取where条件
     * @param  FrontendApiMainController $contll     Controller.
     * @param  array                     $inputDatas 传递的参数.
     * @return array
     */
    private function getWhere(FrontendApiMainController $contll, array $inputDatas)
    {
        $where = [];

        //parent_id
        if (isset($inputDatas['parent_id'])) {
            $parentUser = FrontendUser::find($inputDatas['parent_id']);
            if ($parentUser !== null) {
                $ridArr = explode('|', $parentUser->rid);
                if (in_array($contll->partnerUser->id, $ridArr)) {
                    $where[] = ['parent_id', $inputDatas['parent_id']];
                }
            }
        } else {
            $where[] = ['parent_id', $contll->partnerUser->id];
        }

        //username
        if (isset($inputDatas['username'])) {
            $where[] = ['username', $inputDatas['username']];
        }

        //time_condtions
        if (isset($inputDatas['time_condtions'])) {
            $timeConditions = Arr::wrap(json_decode($inputDatas['time_condtions'], true));
            $where = array_merge($where, $timeConditions);
        }

        //price_group_condtions
        if (isset($inputDatas['price_group_condtions'])) {
            $priceGroupCondtions = Arr::wrap(json_decode($inputDatas['price_group_condtions'], true));
            $where = array_merge($where, $priceGroupCondtions);
        }

        return $where;
    }
}
