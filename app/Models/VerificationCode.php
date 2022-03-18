<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    public function generateTwoFactorCode($email)
    {
        $this->code = rand(100000, 999999);
        $this->email=$email;
        $this->save();
    }

}
