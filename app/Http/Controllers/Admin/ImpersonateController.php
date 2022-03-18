<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginSuccessResource;
use App\Models\User;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        /* @var User $user */
        $user = User::where('id', $request->id)->first();
        $user->token = $user->createToken('IMPERSONATE_TOKEN');

        $user->menus = $user->role->first()->getMenuItems() ?? [];

        return $this->success(
            (array) LoginSuccessResource::make($user),
            'User Impersonation successful!',
        );

    }
}
