<?php

namespace App\Models\User\Fund;

use App\Models\BaseModel;
use App\Models\Game\Lottery\LotteryMethod;
use App\Models\User\Fund\Logics\FrontendUsersAccountsReportLogics;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Project;
use App\Models\Admin\BackendAdminUser;

class FrontendUsersAccountsReport extends BaseModel
{
    use FrontendUsersAccountsReportLogics;

    protected $guarded = ['id'];

    public function changeType()
    {
        $data = $this->hasOne(FrontendUsersAccountsType::class, 'sign', 'type_sign');
        return $data;
    }

    /**
     * @return HasOne
     */
    public function gameMethods(): HasOne
    {
        return $this->hasOne(LotteryMethod::class, 'method_id', 'method_id');
    }

    /**
     * @return HasOne
     */
    public function lottery(): HasOne
    {
        return $this->hasOne(LotteryList::class, 'en_name', 'lottery_id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function admin():HasOne
    {
        return $this->hasOne(BackendAdminUser::class, 'id', 'from_admin_id');
    }
}
