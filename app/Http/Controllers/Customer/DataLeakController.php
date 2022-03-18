<?php

namespace App\Http\Controllers\Customer;

use App\Http\Requests\AddDataLeakEmailRequest;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserEmail;
use Illuminate\Support\Facades\Artisan;

class DataLeakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();

        $userEmail=$user->emails;

        return response()->json(['message'=>'User fetched successfully', 'data'=>$userEmail],200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $user = auth()->user();

        $userEmail = UserEmail::Create([
            'user_id' => $user->id,
            'email' => $request->email
        ]);

        /**
         * @ Scan for data leak breach for newly added email
         */
        Artisan::call('email:scan', [
            'user' => $user->id, 'onlyUnchecked' => true
        ]);

        $user->load('emails');

        return response()->json([
            'message' => "User Email added successfully",
            'data' => [
                'email' => $userEmail,
                'user' => $user
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        UserEmail::destroy($id);
        return response()->json([
            'message' => "User Email deleted successfully"], 200);
    }
}
