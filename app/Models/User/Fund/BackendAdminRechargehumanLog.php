<?php

namespace App\Models\User\Fund;

use App\Models\BackendAdminAuditFlowList;
use App\Models\BaseModel;
use App\Models\User\FrontendUser;
use App\Models\User\UsersRechargeHistorie;
use App\Models\Admin\Fund\BackendAdminRechargePocessAmount;

class BackendAdminRechargehumanLog extends BaseModel
{
    public const DECREMENT = 0; //减少额度
    public const INCREMENT = 1; //增加额度
    public const SYSTEM = 0; //系统操作
    public const SUPERADMIN = 1; //超管操作
    public const ADMIN = 2; //管理员操作
    public const UNDERWAYAUDIT = 0; //待审核
    public const AUDITSUCCESS = 1; //审核通过
    public const AUDITFAILURE = 2; //审核驳回

    protected $guarded = ['id'];

    public function auditFlow()
    {
        return $this->hasOne(BackendAdminAuditFlowList::class, 'id', 'audit_flow_id');
    }

    public function user()
    {
        return $this->hasOne(FrontendUser::class, 'id', 'user_id');
    }

    public function rechargeHistorie()
    {
        return $this->hasOne(UsersRechargeHistorie::class, 'id', 'recharge_id');
    }

    public function adminAmount()
    {
        return $this->hasOne(BackendAdminRechargePocessAmount::class, 'admin_id', 'admin_id');
    }
}
