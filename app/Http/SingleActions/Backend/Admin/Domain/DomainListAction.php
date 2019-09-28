<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\Domain;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainListAction
 * @package App\Http\SingleActions\Backend\Admin\Domain
 */
class DomainListAction
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
     * 域名列表
     * @param  BackEndApiMainController $contll BackEndApiMainController.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['id', 'user_id', 'domain', 'created_at', 'updated_at'];
        $data = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgOut(true, $data);
    }
}
