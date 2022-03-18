<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;


class UserController extends Controller
{


    public function index() {
        return User::with('role')->whereHas('role', function(Builder $query) {
            $query->where('role','!=','Admin');
        })->get();
    }

    public function store(Request $request) {

    }

    public function update(User $user, Request $request) {

    }

    public function destroy(User $user) {

    }


    public function UpdatePassword(UpdatePasswordRequest $request){


        $user = User::findorfail($request->id);

        $user->password = Hash::make($request->password);

        $user->save();

        return $this->success([],"Password updated successfully ");
    }

}
