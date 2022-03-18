<?php

namespace App\Http\Controllers\Consultant;
use App\Http\Controllers\Controller;

use App\Http\Requests\ValidateItConsultantInviteRequest;
use App\Http\Resources\ItConsultantInviteResource;
use App\Mail\SendConsultantInvite;
use App\Models\ConsultantInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ItConsultantInviteController extends Controller
{

    public function index(){

            $user= auth()->user();
            $client=ConsultantInvite::with('client')->where('id', $user->id)->get();
        $token = Crypt::encryptString($user->id);
        return response()->json(
            [
                'client'=>$client,
                'token'=>$token
            ]
        );
    }

    public function inviteUser(ValidateItConsultantInviteRequest $request): \Illuminate\Http\JsonResponse
    {
        $token = Str::random();

        ConsultantInvite::create([
            'token' => $token,
            'user_id' => $request->user()->id,
            'salutation' => $request->get('salutation'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email')
        ]);

        $shareLink = url('register') . '?ref=' . $token;

        $email = $request->get('email');
        $salutation = $request->get('salutation');
        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');

        Mail::to($email)->send(new SendConsultantInvite($email, $salutation, $first_name, $last_name, $shareLink));

        return $this->success([], 'User invited successfully');
    }
}

