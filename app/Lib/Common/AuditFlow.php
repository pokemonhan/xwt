<?php

namespace App\Lib\Common;

use App\Models\BackendAdminAuditFlowList;
use Illuminate\Support\Facades\Cache;

class AuditFlow
{
    /**
     * 插入审核表
     * @param   string $apply_note [备注]
     * @return  int
     */
    public function insertAuditFlow($id, $name, $apply_note): int
    {
        $flowDatas = [
            'admin_id' => $id,
            'apply_note' => $apply_note,
            'admin_name' => $name,
        ];
        $flowConfigure = new BackendAdminAuditFlowList;
        $flowConfigure->fill($flowDatas);
        $flowConfigure->save();
        return $flowConfigure->id;
    }
}
