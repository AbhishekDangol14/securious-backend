<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySize extends Model
{
    use HasFactory;
    protected $fillable=['related_id','related_model','company_size_from','company_size_to'];

    public function companySize(){
        return $this->morphOne();
    }
}
