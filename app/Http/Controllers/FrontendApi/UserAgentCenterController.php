<?php

namespace App\Http\Controllers\FrontendApi;

use App\Http\Requests\Frontend\UserAgentCenter\UserBonusRequest;
use App\Http\Requests\Frontend\UserAgentCenter\UserAgentCenterUserDaysalaryRequest;
use App\Http\Requests\Frontend\UserAgentCenter\UserProfitsRequest;
use App\Http\Requests\Frontend\UserAgentCenter\UserAgentCenterTeamManagementRequest;
use App\Http\Requests\Frontend\UserAgentCenter\UserAgentCenterTeamReportRequest;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserProfitsAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterUserDaysalaryAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserBonusAction;
use App\Http\Requests\Frontend\UserAgentCenter\UserAgentCenterRegisterLinkRequest;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterRegisterableLinkAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterRegisterLinkAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterPrizeGroupAction;
use App\Http\Requests\Frontend\UserAgentCenter\UserAgentCenterLinkDelRequest;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterLinkDelAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterTeamManagementAction;
use App\Http\SingleActions\Frontend\User\AgentCenter\UserAgentCenterTeamReportAction;
use Illuminate\Http\JsonResponse;

/**
 * 代理中心
 */
class UserAgentCenterController extends FrontendApiMainController
{

    /**
     * 用户团队盈亏
     * @param UserProfitsAction  $action  Action.
     * @param UserProfitsRequest $request Request.
     * @return JsonResponse
     */
    public function userProfits(UserProfitsAction $action, UserProfitsRequest $request) : JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 用户日工资
     * @param UserAgentCenterUserDaysalaryRequest $request Request.
     * @param UserAgentCenterUserDaysalaryAction  $action  Action.
     * @return JsonResponse
     */
    public function userDaysalary(UserAgentCenterUserDaysalaryRequest $request, UserAgentCenterUserDaysalaryAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 链接开户信息
     * @param UserAgentCenterRegisterableLinkAction $action Action.
     * @return JsonResponse
     */
    public function registerableLink(UserAgentCenterRegisterableLinkAction $action) :JsonResponse
    {
        return $action->execute($this);
    }


    /**
     * 生成开户链接
     * @param UserAgentCenterRegisterLinkRequest $request Request.
     * @param UserAgentCenterRegisterLinkAction  $action  Action.
     * @return JsonResponse
     */
    public function registerLink(UserAgentCenterRegisterLinkRequest $request, UserAgentCenterRegisterLinkAction $action) :JsonResponse
    {
        return $action->execute($this, $request->validated());
    }

    /**
     * 用户分红
     * @param UserBonusAction  $action  Action.
     * @param UserBonusRequest $request Request.
     * @return JsonResponse
     */
    public function userBonus(UserBonusAction $action, UserBonusRequest $request) :JsonResponse
    {
        return $action->execute($this, $request);
    }

    /**
     * 代理开户-奖金组最大最小值
     * @param UserAgentCenterPrizeGroupAction $action Action.
     * @return JsonResponse
     */
    public function prizeGroup(UserAgentCenterPrizeGroupAction $action) :JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 开户链接删除
     * @param UserAgentCenterLinkDelRequest $request Request.
     * @param UserAgentCenterLinkDelAction  $action  Action.
     * @return JsonResponse
     */
    public function linkDel(UserAgentCenterLinkDelRequest $request, UserAgentCenterLinkDelAction $action) :JsonResponse
    {
        return $action->execute($this, $request->validated());
    }

    /**
     * 团队管理
     * @param  UserAgentCenterTeamManagementRequest $request Request.
     * @param  UserAgentCenterTeamManagementAction  $action  Action.
     * @return JsonResponse
     */
    public function teamManagement(UserAgentCenterTeamManagementRequest $request, UserAgentCenterTeamManagementAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 团队报表
     * @param  UserAgentCenterTeamReportRequest $request Request.
     * @param  UserAgentCenterTeamReportAction  $action  Action.
     * @return JsonResponse
     */
    public function teamReport(UserAgentCenterTeamReportRequest $request, UserAgentCenterTeamReportAction $action) :JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
