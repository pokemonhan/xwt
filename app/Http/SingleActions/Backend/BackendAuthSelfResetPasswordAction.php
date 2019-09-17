<?php

namespace App\Http\SingleActions\Backend;

use App\Http\Controllers\BackendApi\BackendAuthController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class BackendAuthSelfResetPasswordAction
{
    /**
     * change partner user Password
     * @param  BackendAuthController  $contll
     * @param  array $inputDatas
     * @return JsonResponse
     */
    public function execute(BackendAuthController $contll, array $inputDatas): JsonResponse
    {

        if (!Hash::check($inputDatas['old_password'], $contll->partnerAdmin->password)) {
            return $contll->msgOut(false, [], '100003');
        } else {
            $token = $contll->refresh();
            $contll->partnerAdmin->password = Hash::make($inputDatas['password']);
            $contll->partnerAdmin->remember_token = $token;
            try {
                $contll->partnerAdmin->save();
                $expireInMinute = $contll->currentAuth->factory()->getTTL();
                $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
                $data = [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $expireAt,
                ];
                return $contll->msgOut(true, $data);
            } catch (Exception $e) {
                return $contll->msgOut(false, [], $e->getCode(), $e->getMessage());
            }
        }
    }
}
