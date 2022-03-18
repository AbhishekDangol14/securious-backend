<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAsset;

class Company extends Model
{
    use HasFactory,HasAsset;

    protected $fillable=['user_id','company_name','company_website','company_size','business_address','legal_role_id','industry_id','company_role_id','stripe_id','last_assets_update','recommendation_view_limit'];

    public function profile(){
        return $this->hasMany(Profile::class);
    }

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function threatsForCompany(){
        $companySize=$this->company_size;
        $industry=$this->industry_id;
        $assets=$this->assetRelation()->pluck('solution_partner_product_id')->toArray();
        return Threat::with('translations')->where(function ($q) use ($companySize, $assets, $industry) {
            $q->active()
                ->havingCompanySize($companySize)
                ->havingAssets($assets)
                ->havingQuestions($companySize, $assets, $industry)
                ->relatedToIndustries([$industry]);
        });

    }

    public function neutralizedThreats()
    {
        return Threat::neutralized($this->id);
    }

    public function toReAnalyzedThreats()
    {
        return Threat::toReAnalyzed($this->id);
    }

    public function getTotalPointsForAnalyzedThreat(Threat $threat)
    {
        return CustomerRecommendation::where('customer_recommendations.threat_id', $threat->id)->where('company_id', $this->id)
            ->join('recommendations', 'recommendations.id', 'customer_recommendations.recommendation_id')
            ->sum('recommendations.points');
    }

    public function getTotalObtainablePointsForAnalyzedThreat(Threat $threat)
    {
        return CustomerRecommendation::where('customer_recommendations.threat_id', $threat->id)->where('company_id', $this->id)
            ->where('status', CustomerRecommendation::$STATUS_COMPLETED)->join('recommendations', 'recommendations.id', 'customer_recommendations.recommendation_id')
            ->sum('recommendations.points');
    }
}
