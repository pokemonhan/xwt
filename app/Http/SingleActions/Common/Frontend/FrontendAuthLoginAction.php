<?php

namespace App\Http\SingleActions\Common\Frontend;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class FrontendAuthLoginAction
{
    use AuthenticatesUsers;

    private $userAgent;
    private $maxAttempts;
    private $decayMinutes;

    /**
     * Login user and create token
     * @param  FrontendApiMainController  $contll
     * @param  Request $request
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, Request $request): JsonResponse
    {
        $this->userAgent = $contll->userAgent;
        $request->validate([
            'username' => 'required|string|alpha_dash',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['username', 'password']);
        $this->maxAttempts = 1; //1 times
        $this->decayMinutes = 1; //1 minutes

        if (!$token = $contll->currentAuth->attempt($credentials)) {
            return $contll->msgOut(false, [], '100002');
        }
        if ($contll->currentAuth->user()->frozen_type === 1) {
            return $contll->msgOut(false, [], '100014');
        }

        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        $expireInMinute = $contll->currentAuth->factory()->getTTL();
        $expireAt = Carbon::now()->addMinutes($expireInMinute)->format('Y-m-d H:i:s');
        $user = $contll->currentAuth->user();
        if ($user->remember_token !== null) {
            try {
                JWTAuth::setToken($user->remember_token);
                JWTAuth::invalidate();
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
        }
        $user->remember_token = $token;
        $user->last_login_ip = request()->ip();
        $user->last_login_time = Carbon::now()->timestamp;
        $user->save();
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt,
        ];
        return $contll->msgOut(true, $data);
    }

    protected function throttleKey(Request $request):  ? string
    {
        if ($this->userAgent->isDesktop()) {
            return Str::lower($request->input($this->username())) . '|Desktop|' . $request->ip();
        } else {
            return Str::lower($request->input($this->username())) .
            '|' . $this->userAgent->device() .
            '|' . $request->ip();
        }
    }
    protected function username() : string
    {
        return 'username';
    }
}
