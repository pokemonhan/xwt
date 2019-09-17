<?php

namespace App\Http\SingleActions\Common\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BackendAuthLogoutAction
{
    use AuthenticatesUsers;
    /**
     * Logout user (Revoke the token)
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $request): JsonResponse
    {
        $throtleKey = Str::lower($contll->partnerAdmin->email . '|' . $request->ip());
        $request->session()->invalidate();
        $this->limiter()->clear($throtleKey);
        $contll->currentAuth->logout();
        $contll->currentAuth->invalidate();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
