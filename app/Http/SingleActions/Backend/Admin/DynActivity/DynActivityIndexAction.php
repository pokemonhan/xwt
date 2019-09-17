<?php

namespace App\Http\SingleActions\Backend\Admin\DynActivity;

use App\Http\Controllers\BackendApi\Admin\DynActivity\DynActivityController;
use App\Models\Admin\DynActivity\BackendDynActivityList;
use Illuminate\Http\JsonResponse;

class DynActivityIndexAction
{
    protected $model;

    public function __construct(BackendDynActivityList $backendDynActivityList)
    {
        $this->model = $backendDynActivityList;
    }

    public function execute(DynActivityController $contll) :JsonResponse
    {
        $searchAbleFields = ['name'];
        $orderFields = 'sort';
        $orderFlow = 'asc';
        $datas['lists'] = $contll->generateSearchQuery($this->model, $searchAbleFields, 0, null, [], $orderFields, $orderFlow);
        $activity_cfg = config('activity_cfg');
        $rules = [];
        if (!is_null($activity_cfg)) {
            foreach ($activity_cfg as $key1 => $val1) {
                $tmpData = [];
                foreach ($val1 as $key2 => $val2) {
                    $tmpData[] = [
                        'rule_file' => $key2,
                        'name' => $val2['name'] ?? '',
                        'description' => $val2['description'] ?? ''
                    ];
                }
                $rules[$key1] = $tmpData;
                unset($tmpData);
            }
        }
        $datas['rules'] = $rules;
        return $contll->msgOut(true, $datas);
    }
}
