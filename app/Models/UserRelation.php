<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    use HasFactory;

    protected $fillable=['parent_id', 'child_id'];
    public const ACTIVE_STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 0,
    ];
}
