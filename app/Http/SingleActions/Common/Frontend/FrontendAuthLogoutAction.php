<?php

namespace App\Http\SingleActions\Common\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FrontendAuthLogoutAction
{
    use AuthenticatesUsers;
    /**
     * Login user and create token
     * @param  FrontendApiMainController  $contll
     * @param  Request $request
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, Request $request): JsonResponse
    {
        $throtleKey = Str::lower($this->username() . '|' . $request->ip());
        $request->session()->invalidate();
        $this->limiter()->clear($throtleKey);
        $contll->currentAuth->logout();
        $contll->currentAuth->invalidate();
        return $contll->msgOut(true); //'Successfully logged out'
    }
}
