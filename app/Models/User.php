<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public const ROLE_EMPLOYEE = 'employee';
    public const ROLE_COMPANY = 'company';
    public const ROLE_CONSULTANT = 'consultant';


    public const ROLES = [
        self::ROLE_EMPLOYEE,
        self::ROLE_COMPANY,
        self::ROLE_CONSULTANT
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at'=>'datetime'
    ];

    public static function find(mixed $id)
    {

    }

    public function role(): \Illuminate\Database\Eloquent\Relations\belongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = rand(100000, 999999);

        $this->two_factor_expires_at = now()->addMinutes(1);

        $this->save();

    }

    /**
     * Reset the MFA code generated earlier
     */
    public function resetTwoFactorCode()
    {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function emails(){
       return $this->hasMany(UserEmail::class)->with('breaches','breaches.classes');
    }

    public function company(){
        return $this->hasOne(Company::class);
    }

    public function threatsForCustomer()
    {
        return $this->company->threatsForCompany() ?? null;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function neutralizedThreats()
    {
        return $this->company->neutralizedThreats();
    }

    public function toReAnalyzedThreats()
    {
        return $this->company->toReAnalyzedThreats();
    }

    public function getTotalPointsForAnalyzedThreat(Threat $threat)
    {
        return $this->company->getTotalPointsForAnalyzedThreat($threat);
    }

    public function getTotalObtainablePointsForAnalyzedThreat(Threat $threat)
    {
        return $this->company->getTotalObtainablePointsForAnalyzedThreat($threat);
    }
}
