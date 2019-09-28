<?php

namespace App\Http\SingleActions\Backend\Admin\Domain;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\Domain\BackendDomain;
use Illuminate\Http\JsonResponse;

/**
 * Class DomainListAction
 * @package App\Http\SingleActions\Backend\Admin\Domain
 */
class DomainListAction
{
    /**
     * @var BackendDomain
     */
    protected $model;

    /**
     * @param  BackendDomain $backendDomain BackendDomain.
     */
    public function __construct(BackendDomain $backendDomain)
    {
        $this->model = $backendDomain;
    }

    /**
     * 域名列表
     * @param  BackEndApiMainController $contll BackEndApiMainController.
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['id', 'user_id', 'domain', 'config_id', 'is_use', 'created_at', 'updated_at'];
        $data = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgOut(true, $data);
    }
}
