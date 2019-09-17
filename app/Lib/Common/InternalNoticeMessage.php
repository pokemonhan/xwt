<?php

namespace App\Lib\Common;

use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Message\BackendSystemInternalMessage;
use App\Models\Admin\Message\BackendSystemNoticeList;

class InternalNoticeMessage
{
    protected $articlesMessage = '有新的文章需要审核';
    /**
     * 生成backend_system_notice_lists表
     * @param  int     $type      1手动发送 2审核相关 3充值体现相关
     * @param  string  $message   消息内容
     */
    public function createNoticeMessages($type, $message)
    {
        $data = [
            'type' => $type,
            'message' => $message,
        ];
        $noticeMessage = new BackendSystemNoticeList();
        $noticeMessage->fill($data);
        $noticeMessage->save();
        return $noticeMessage->id;
    }

    /**
     * 生成interna_notice表
     * @param  array    $adminsArr   接收信息的管理员Eloq
     * @param  int      $message_id  backend_system_notice_lists表id
     * @param  int|bool $operate_admin_id  发送人id,系统null
     * @return void
     */
    public function createInternalNotice($adminsArr, $message_id, $operate_admin_id = null)
    {
        foreach ($adminsArr as $admin) {
            $data = [
                'operate_admin_id' => $operate_admin_id,
                'receive_admin_id' => $admin['id'],
                'receive_group_id' => $admin['group_id'],
                'message_id' => $message_id,
                'status' => 0,
            ];
            $noticeMessage = new BackendSystemInternalMessage();
            $noticeMessage->fill($data);
            $noticeMessage->save();
        }
    }

    /**
     * 插入站内消息
     */
    public function insertMessage($type, $message, $adminsArr, $send_id = null)
    {
        $message_id = $this->createNoticeMessages($type, $message);
        $this->createInternalNotice($adminsArr, $message_id, $send_id);
    }

    public function adminAddArticles()
    {
        $type = BackendSystemNoticeList::AUDIT;
        $message = $this->articlesMessage;
        $adminsArr = BackendAdminUser::select('id', 'group_id')->where('group_id', 1)->get()->toArray();
        $this->insertMessage($type, $message, $adminsArr);
    }
}
