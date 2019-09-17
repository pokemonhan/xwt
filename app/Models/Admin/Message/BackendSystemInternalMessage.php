<?php

namespace App\Models\Admin\Message;

use App\Models\Admin\BackendAdminUser;
use App\Models\BaseModel;

class BackendSystemInternalMessage extends BaseModel
{
    protected $guarded = ['id'];

    protected $casts = array('created_at' => 'created_at', 'updated_at' => 'updated_at');

    public function noticeMessage()
    {
        return $this->hasOne(BackendSystemNoticeList::class, 'id', 'message_id');
    }
}
