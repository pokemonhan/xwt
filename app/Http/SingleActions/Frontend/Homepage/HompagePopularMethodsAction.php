<?php

namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryFnfBetableList;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class HompagePopularMethodsAction
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
     * 热门彩票二-玩法
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $lotteriesEloq = $this->model::select('show_num', 'status')->where('en_name', 'popularLotteries.two')->first();
        if ($lotteriesEloq === null || $lotteriesEloq->status !== 1) {
            return $contll->msgOut(false, [], '100400');
        }
        $popularMethodListEloq = FrontendLotteryFnfBetableList::orderBy('sort', 'asc')
            ->limit($lotteriesEloq->show_num)
            ->with(['method', 'currentIssue:lottery_id,issue,end_time'])
            ->get();
        $datas = [];
        if ($popularMethodListEloq->isNotEmpty()) {
            foreach ($popularMethodListEloq as $methodItem) {
                $data = [
                    'lotteries_id' => $methodItem->lotteries_id,
                    'lottery_name' => $methodItem->method->lottery_name,
                    'method_name' => $methodItem->method->method_name,
                    'method_group' => $methodItem->method->method_group,
                    'method_id' => $methodItem->method->method_id,
                    'issue' => $methodItem->currentIssue->issue ?? null,
                    'end_time' => $methodItem->currentIssue->end_time ?? null,
                ];
                $datas[] = $data;
            }
        }
        return $contll->msgOut(true, $datas);
    }
}
