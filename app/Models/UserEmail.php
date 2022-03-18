<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'checked',
        'email'
    ];

    public function breaches()
    {

        return $this->belongsToMany(
            BreachedWebsite::class,
            'user_email_breaches',
            'email_id',
            'breach_id'
        );
    }


}
