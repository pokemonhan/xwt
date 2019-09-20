<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\Admin\BackendAdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class BackendAuthUpdatePAdmPasswordAction
{
    protected $model;

    /**
     * @param  BackendAdminUser  $backendAdminUser
     */
    public function __construct(BackendAdminUser $backendAdminUser)
    {
        $this->model = $backendAdminUser;
    }

    /**
     * @param  BackEndApiMainController  $contll
     * @param  array                     $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, array $inputDatas): JsonResponse
    {
        $targetUserEloq = $this->model::where([
            ['id', '=', $inputDatas['id']],
            ['name', '=', $inputDatas['name']],
        ])->first();
        if ($targetUserEloq !== null) {
            try {
                $targetUserEloq->password = Hash::make($inputDatas['password']);
                $targetUserEloq->save();
                return $contll->msgOut(true);
            } catch (\Exception $exception) {
                return $contll->msgOut(false, [], $exception->getCode(), $exception->getMessage());
            }
        } else {
            return $contll->msgOut(false, [], '100004');
        }
    }
}
