<?php

namespace App\Models\Admin;

use App\Models\BackendAdminAuditFlowList;
use App\Models\BaseModel;

class BackendAdminAuditPasswordsList extends BaseModel
{
    protected $guarded = ['id'];

    public function auditFlow()
    {
        return $this->hasOne(BackendAdminAuditFlowList::class, 'id', 'audit_flow_id');
    }
}
