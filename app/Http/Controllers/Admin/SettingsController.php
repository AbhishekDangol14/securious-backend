<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Mail;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Carbon;

class SettingsController extends Controller
{
    public function registerGoogle2FA()
    {
        $user = auth()->user();
        /** @var Google2FA $google2fa */
        $google2fa = app('pragmarx.google2fa');
        $secret_key = $google2fa->generateSecretKey();
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret_key
        );

//        $QR_Image = base64_encode($QR_Image);

        $data = [
            'QR_Image' => $QR_Image,
            'secret' => $secret_key
        ];

        return response()->json(['message' => 'success', 'data' => $data]);
    }

    public function validateGoogleSecret(Request $request)
    {
        $request->validate([
            'verification_code' => 'required'
        ]);

        $google2fa = app('pragmarx.google2fa');

        $secret = $request['verification_code'];
        $window = 8; // 8 keys (respectively 4 minutes) past and future
        $valid = $google2fa->verifyKey($request['google_2fa_secret'], $secret, $window);
        if ($valid == true) {
            auth()->user()->update([
                'google2fa_secret' => $request['google_2fa_secret'],
            ]);

            Redis::set('isLoggedIn', 1);

            return response()->json(['message' => 'success', 'status' => "true"]);
        }
        return response()->json(['validation' => "Validation Error", 'status' => 'failed']);
    }

    public function enableEmail2FA(Request $request)
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        Mail::to($user->email)->send(new TwoFactorCodeMail($user->two_factor_code, 'Enable Two-factor code'));

        return response()->json(['message' => 'success', 'status' => "true"]);
    }

    public function disable2FA(Request $request): JsonResponse
    {

        $request->validate([
            'type'=>'required'
        ]);

        $user = auth()->user();


        if ($request['type'] == 'google2fa') {
            $user->google_2fa_enabled = 0;
            $user->save();
        }
        elseif  ($request['type'] == 'email2fa') {
            $user->email_2fa_enabled= 0;
            $user->save();
        }
        return response()->json(['message' => "success"], 200);
    }

    public function validateEmailSecret(Request $request)
    {
        $request->validate([
            'emailVerification_code' => "required"
        ]);

        $user = auth()->user();

        if ($user->two_factor_code == $request['emailVerification_code']) {

            if(isset($user->two_factor_expires_at) && $user->two_factor_expires_at->lt(Carbon::now()->toDateTime())){

                $user->resetTwoFactorCode();
                return('Code Expired');

                $user->two_factor_code=null;
                $user->email_2fa_enabled=1;
                $user->save();

                Redis::set('isLoggedIn', 1);

                return response()->json(['message' => 'success', 'status' => "true"]);
            }
        }

        return response()->json(['validation' => "The two-factor-code does not match", 'status' => 'failed']);
    }
}
