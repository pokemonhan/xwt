<?php
namespace App\Http\SingleActions\Frontend\User\AgentCenter;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\UserAgentCenter\UserBonusRequest;
use App\Models\User\UserBonus;
use Illuminate\Http\JsonResponse;

class UserBonusAction
{
    protected $model;

    public function __construct(UserBonus $userBonus)
    {
        $this->model = $userBonus;
    }

    /**
     * 代理分红api
     * @param  FrontendApiMainController $contll
     * @param  UserBonusRequest $request
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, UserBonusRequest $request): JsonResponse
    {
        $data = [];

        $username = $request->input('username') ?? '';
        $dateTo = $request->input('date') ?? date('Y-m-d');
        $count = $request->input('count') ?? 15;

        $userInfo = $contll->currentAuth->user();

        $where = [['parent_user_id', $userInfo->id], ['period', '=', $dateTo]];

        if (!empty($username)) {
            $where = array_merge([['username', $username]], $where);
        }

        //自己

        $data['self'] = $this->model->where([
            ['period', '=', $dateTo],
            ['user_id', $userInfo->id],
        ])->first();

        //下级
        $data['child'] = $this->model->where($where)->paginate($count);

        return $contll->msgOut(true, $data);
    }
}
