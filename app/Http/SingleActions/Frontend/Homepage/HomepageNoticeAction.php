<?php

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Notice\FrontendMessageNotice;
use App\Models\Admin\Notice\FrontendMessageNoticesContent;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;

class HomepageNoticeAction
{
    protected $model;

    /**
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 首页 公告|站内信 列表
     * @param FrontendApiMainController $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $noticeEloq = $this->model::select('show_num', 'status')->where('en_name', 'notice')->first();
        if ($noticeEloq->status !== 1) {
            return $contll->msgOut(false, [], '100400');
        }
        $data = [];
        if ((int) $inputDatas['type'] === FrontendMessageNoticesContent::TYPE_NOTICE) {
            $data = $this->getNoticeList($contll);
        } elseif ((int) $inputDatas['type'] === FrontendMessageNoticesContent::TYPE_MESSAGE) {
            $data = $this->getMessageList($contll);
        }
        return $contll->msgOut(true, $data);
    }

    //公告列表
    public function getNoticeList($contll)
    {
        $eloqM = new FrontendMessageNoticesContent();
        //仅查询未过期公告
        $time = [
            ['start_time','<=',date('Y-m-d H:i:s',time())],
            ['end_time','>=',date('Y-m-d H:i:s',time())]
        ];
        $timeStr = json_encode($time);
        $contll->inputs['time_condtions'] = $timeStr;
        $contll->inputs['status'] = FrontendMessageNoticesContent::STATUS_OPEN; //仅查询开启状态公告
        $searchAbleFields = ['type', 'status'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        return $contll->generateSearchQuery($eloqM, $searchAbleFields, 0, null, null, $orderFields, $orderFlow);
    }

    //站内信列表
    public function getMessageList($contll)
    {
        $eloqM = new FrontendMessageNotice();
        $contll->inputs['receive_user_id'] = $contll->partnerUser->id ?? null;
        $searchAbleFields = ['status', 'receive_user_id'];
        $fixedJoin = 1;
        $withTable = 'messageContent';
        $withSearchAbleFields = ['type'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $messages = $contll->generateSearchQuery(
            $eloqM,
            $searchAbleFields,
            $fixedJoin,
            $withTable,
            $withSearchAbleFields,
            $orderFields,
            $orderFlow
        );
        $data['message'] = $messages;
        $data['unread_num'] = $contll->partnerUser->unreadMessageNum(); //获取站内所有未读消息
        return $data;
    }
}
