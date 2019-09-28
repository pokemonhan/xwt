<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\Domain;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainAddAction
 * @package App\Http\SingleActions\Backend\Admin\Domain
 */
class DomainAddDomainAction
{
    /**
     * @var Domain
     */
    protected $model;

    /**
     * @param  Domain $domain Domain.
     */
    public function __construct(Domain $domain)
    {
        $this->model = $domain;
    }

    /**
     * 添加域名
     * @param  BackEndApiMainController $contll     BackEndApiMainController.
     * @param  array                    $inputDatas 请求数据.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $haveFlag = $this->model->where('domain', $inputDatas['domain'])->count();
        if ($haveFlag > 0) {
            return $contll->msgOut(false, [], '102500');
        }
        try {
            $domainEloq = new $this->model();
            $domainEloq->fill($inputDatas);
            $domainEloq->save();
            return $contll->msgOut(true);
        } catch (\Exception $e) {
            return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
        }
    }
}
