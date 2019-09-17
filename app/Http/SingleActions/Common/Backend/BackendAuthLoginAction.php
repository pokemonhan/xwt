<?php

namespace App\Http\SingleActions\Common\Backend;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class BackendAuthLoginAction
{
    use AuthenticatesUsers;

    private $maxAttempts;
    private $decayMinutes;

    /**
     * Login user and create token
     * @param  BackEndApiMainController  $contll
     * @param  Request $request
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['email', 'password']);
        $this->maxAttempts = 1; //1 times
        $this->decayMinutes = 1; //1 minutes
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        /*
        if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        $seconds = $this->limiter()->availableIn(
        $this->throttleKey($request)
        );
        return $contll->msgOut(false, [], '100005');
        }
         */

        if (!$token = $contll->currentAuth->attempt($credentials)) {
            return $contll->msgOut(false, [], '100002');
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
        $user->save();
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expireAt,
        ];
        return $contll->msgOut(true, $data);
    }
}
