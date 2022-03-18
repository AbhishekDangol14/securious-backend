<?php

namespace App\Models;

use App\Traits\HasIndustry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryRelation extends Model
{
    use HasFactory, HasIndustry;

    protected $fillable=['related_id','related_model','status','industry_id','status'];

    public function industryRelation(){
        return $this->morphToMany();
    }
}
