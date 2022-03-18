<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginSuccessResource;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{

    public function login(LoginRequest $request) {

        $user = User::where('email', $request->get('email'))->first();

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'The user does not exist!'
            ]);
        }

        $isSamePassword = Hash::check($request->get('password'), $user->password);

        if ($isSamePassword) {
            $user->token = $user->createToken('DEFAULT_LOGIN')->plainTextToken;

           $user->menus = $user->role->first()->getMenuItems() ?? [];

           Redis::set('isLoggedIn',0);

            return $this->success(
                (array) LoginSuccessResource::make($user),
                'Login successful!',
                200
            );
        }

        return response()->json([
            'status' => 401,
            'message' => 'The username and password do not match!'
        ],422);

    }
}
