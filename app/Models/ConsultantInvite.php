<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salutation',
        'first_name',
        'last_name',
        'email',
        'token'
    ];

    public const INVITATION_TYPE = [
        'Consultant' => 'Company',
        'Company' => 'Employee',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Returns the user who is also owner of this invite
     */
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function consultant(){
        return $this->belongsTo(User::class);
    }
}
