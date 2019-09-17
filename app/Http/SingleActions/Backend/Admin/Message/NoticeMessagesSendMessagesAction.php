<?php

namespace App\Http\SingleActions\Backend\Admin\Message;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Message\BackendSystemNoticeList;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NoticeMessagesSendMessagesAction
{
    /**
     * 手动发送站内信息
     * @param   BackEndApiMainController  $contll
     * @param   array $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $adminsArr = BackendAdminUser::select('id', 'group_id')
            ->whereIn('id', $inputDatas['admins_id'])->get()->toArray();
        DB::beginTransaction();
        try {
            $messageObj = new InternalNoticeMessage();
            $type = BackendSystemNoticeList::ARTIFICIAL;
            $message = $inputDatas['message'];
            $messageObj->insertMessage($type, $message, $adminsArr, $contll->partnerAdmin->id);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
