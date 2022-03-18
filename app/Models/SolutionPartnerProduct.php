<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;
use App\Traits\HasTranslations;
use App\Traits\HasIndustry;
use App\Traits\HasCompanySize;

class SolutionPartnerProduct extends Model
{
    use HasFactory,Mediable,HasTranslations,HasIndustry,HasCompanySize;

    protected $fillable = ['show_if_industry', 'show_if_company_size','solution_partner_id','is_solution_partner','is_company_asset','product_link','is_active'];

    public function solutionpartner(){
        return $this->belongsTo(SolutionPartner::class);
    }

    public function assetAlert(){
        return $this->hasMany(AssetAlert::class);
    }
    public function company(){
        return $this->belongsToMany(Company::class);
    }
}
