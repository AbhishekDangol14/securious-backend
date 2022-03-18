<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class TwoFactorAuthenticationController extends Controller
{
    public function verifyEmail2FA(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        $user = auth()->user();

        if ($request->input('two_factor_code') == $user->two_factor_code) {

            Redis::set('isLoggedIn',1);

            $user->resetTwoFactorCode();

            return response()->json([
                'type' => 'success']);
        }

//        $msg = trans('messages.incorrect_or_expire');
        return response()->json([
            'type' => 'error'
        ]);

    }

    public function verifyGoogle2FA(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        $user = auth()->user();

        $google2fa = new Google2FA();

        $secret = $request['two_factor_code'];

        $window = 8; // 8 keys (respectively 4 minutes) past and future

        $valid = false;
        if ($user->google_2fa_enabled == 1) {
            $valid = $google2fa->verifyKey($user->google2fa_secret, $secret, $window);
        }

        if ($valid == true) {

            Redis::set('isLoggedIn',1);

            $user->resetTwoFactorCode();

            return response()->json([
                'type' => 'success'
            ]);
        }

//        $msg = trans('messages.incorrect_or_expire');
        return response()->json([
            'type' => 'error'
        ]);

        //return redirect()->back()->withErrors($msg)->withInput()->with('error', $msg);
    }

    public function resend()
    {
        $user = auth()->user();

        $user->generateTwoFactorCode();

        Mail::to($user->email)->send(new TwoFactorCodeMail($user->two_factor_code));

        return redirect()->back()->with('success', trans('A fresh verification code has been sent to your email address.'));
    }
}
