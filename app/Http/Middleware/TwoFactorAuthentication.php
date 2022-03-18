<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class TwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

//        if(isset($user->two_factor_expires_at) && $user->two_factor_expires_at->lt(now())){
//            $user->resetTwoFactorCode();
//            auth()->logout();
//            return('Code Expired');
////              return redirect()->route('login')->withMessage(['alert' => 'The two Factor code has been Expired. Please try new']);
//        }

       if ($user->email_2fa_enabled === 1 && !Redis::get('isLoggedIn')) {

         dd('xyz');
          //Route to Email2FA
        }


        if ($user->google_2fa_enabled === 1 && !Redis::get('isLoggedIn')) {
          dd('pqr');
           //Route to Google2FA

        }

       return $next($request);
    }
}
