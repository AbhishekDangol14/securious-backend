<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailBreach extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'logo',
        'date',
        'logo'
    ];

    /**
     * Return the relationship to breach classes with this breach website model.
     *
     * @return BelongsToMany
     */

}
