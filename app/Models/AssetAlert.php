<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAlert extends Model
{
    use HasFactory;

    protected $fillable = ['risk_level','date','link','solution_partner_id'];
}
