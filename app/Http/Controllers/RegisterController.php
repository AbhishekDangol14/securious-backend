<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;

use App\Http\Resources\RegistrationSuccessResponse;
use App\Models\ConsultantInvite;
use App\Models\User;
use App\Models\Role;

use App\Models\UserRelation;
use App\Models\VerificationCode;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
use function in_array;



class RegisterController extends Controller
{
    public function __construct(UserService $service){
        $this->service=$service;
    }

    public function register(RegisterRequest $request){

        $user=new User();
        $user->email=$request->get('email');
        $user->password=bcrypt($request->get('password'));
        $user->save();

        DB::table('user_roles')->insert([
            'user_id'=>$user->id,
            'role_id'=>$request->role
        ]);

        if ($request->has('ref') ){
           $parent_id= Crypt::decryptString($request->get('ref'));
           UserRelation::insert([
               'parent_id'=>$parent_id,
                'child_id'=>$user->id
               ]);
        }

        $verification =new VerificationCode();

        $verification->generateTwoFactorCode($request->get('email'));

        $user->token = $user->createToken('DEFAULT_LOGIN')->plainTextToken;

        $user->menus = $user->role->first()->getMenuItems() ?? [];

        $user->role = $user->role()->get();

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'data' => RegistrationSuccessResponse::make($user)
        ], 200);

    }
    public function verification (Request $request){
        $validated_user=VerificationCode::where('email',$request->email)->where('code',$request->code)->first();

        // if ($validated_user && $validated_user->updated_at <= Carbon::now()->subMinutes(2)){
        if ($validated_user){
            User::where('email',$request->email)->update([
                'email_verified_at' => Carbon::now()
            ]);
            return $this->success(Carbon::now(),'success');
        }

        return $this->success([],'failed',401);
    }

    public function test( $request, $user)
    {
        $consultantId =Crypt::decryptString ($request->ref);
        UserRelation::insert(
          ['parent_id'=>$consultantId,
              'child_id'=>$user->id]
      );

    }

    public function showRegistrationForm(Request $request, ?string $role = null){

        if ($request->has('ref') ){
            $consultant_id = Crypt::decryptString($request->get('ref'));
            $consultant = $this->service->find($consultant_id);
            Redis::set('consReferrerId',$consultant_id);
            return $consultant;
        }
        
        if(isset($role)){
          $role_id = Role::where('role','LIKE','%'.$role.'%')->pluck('id')->first();
          if(!$role_id){
              return $this->success([],'invalid code',404);
          }
          return $this->success($role_id,'success');
        }
        
        $role_id = Role::where('role','customer')->pluck('id')->first();
        
        return $this->success($role_id,'success');
    }


    public function generateTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = rand(100000, 999999);

        $this->two_factor_expires_at = now()->addMinutes(1);

        $this->save();

    }
}
