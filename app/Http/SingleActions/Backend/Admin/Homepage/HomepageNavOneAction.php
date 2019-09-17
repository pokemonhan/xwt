<?php

namespace App\Http\SingleActions\Backend\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;

class HomepageNavOneAction
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
     * 导航一列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $frontendModuleEloq = new $this->model;
        $navEloq = $frontendModuleEloq->getModuleEloq('nav.one');
        $datas = $this->model::select(
            'id',
            'label',
            'en_name',
            'value',
            'show_num',
            'status'
        )->where('pid', $navEloq->id)->get()->toArray();
        return $contll->msgOut(true, $datas);
    }
}
