<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantCompanySize extends Model
{
    use HasFactory;

    protected $fillable=['company_size_from','company_size_to','threat_id'];

}
