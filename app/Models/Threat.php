<?php

namespace App\Models;

use App\Traits\HasAsset;
use App\Traits\HasCategory;
use App\Traits\HasCompanySize;
use App\Traits\HasIndustry;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class Threat extends Model
{
    use HasFactory, HasTranslations, Mediable, HasIndustry, HasCompanySize, HasAsset, HasCategory;
    protected $fillable=['estimated_time_in_minutes','video_link','is_always_important','important_if_industry_id','important_if_company_size','is_display_active_always','show_if_industry','show_if_company_size','show_if_using_asset','status'];

    public function importantIndustry(){
        return $this->belongsToMany(Industry::class, "important_industries", 'threat_id','industry_id');
    }

    public function importantCompanySize(){
        return $this->hasOne(ImportantCompanySize::class);
    }

    public function analysisQuestion(){
       return $this->hasMany(AnalysisQuestion::class)->orderBy('order');
    }

    public function recommendation(){

        return $this->hasMany(Recommendation::class)->orderBy('order');
    }
    
    public function scopeNotNeutralized($query, $companyId)
    {
        return $query->whereDoesntHave('threats_neutralized', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });
    }

    public function scopeHavingCompanySize($query, $from = 20)
    {
        return $query->where(function ($q) use ($from) {
            $q->where('show_if_company_size', 0);
            if ($from <= 500) $q->orWhereHas('companySize',function ($q2) use ($from) {
                $q2->where('company_size_from', '<=', $from)->where('company_size_to', '>=', $from);
            });
            else $q->orWhereHas('companySize',function ($q2) use ($from) {
                $q2->where('company_size_from', '<=', $from)->where('company_size_to', '>=', 500);
            });
        });
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_display_active_always', 1);
    }
    
    public function scopeRelatedToIndustries($query, $industries = [])
    {
        return $query->where(function ($q) use ($industries) {
            $q->where('show_if_industry', 0)->orWhereHas('industryRelation', function ($q) use ($industries) {
                $q->whereIn('industry_id', $industries);
            });
        });
    }

    public function scopeHavingQuestions($query, $companySize, $assets, $industry)
    {
        return $query->whereHas('analysisQuestion', function ($q) use ($companySize, $assets, $industry) {
            $q->havingCompanySize($companySize)
                ->havingAssets($assets)
                ->relatedToIndustries([$industry]);
        });
    }
    
    public function scopeHavingAssets($query, $assets = [])
    {
        return $query->where(function ($q) use ($assets) {
            $q->where('show_if_using_asset', 0)->orWhereHas('assetRelation', function ($q) use ($assets) {
                $q->whereIn('solution_partner_product_id', $assets);
            });
        });
    }

    /**
     * Return a has one relation of threats translation related to this threats.
     * Return a has many relation of threats neutralized related to this threats.
     *
     * @return HasMany
     */
    public function threats_neutralized()
    {
        return $this->hasMany(
            ThreatNeutralized::class,
            'threat_id',
            'id'
        );
    }

    public function scopeNeutralized($query, $companyId)
    {
        return $query->whereHas('threats_neutralized', function ($q) use ($companyId) {
            $q->where('company_id', $companyId)
                ->where('recheck_status', ThreatNeutralized::STATUS_DOESNOT_NEED_RECHECK);
        });
    }

    public function scopeToReAnalyzed($query, $companyId)
    {
        return $query->whereHas('threats_neutralized', function ($q) use ($companyId) {
            $q->where('company_id', $companyId)
                ->where('recheck_status', ThreatNeutralized::STATUS_NEEDS_RECHECK);
        });
    }

    public function isNeutralized(Company $company)
    {
        return ThreatNeutralized::where('company_id', $company->id)->where('threat_id', $this->id)->first() != null;
    }

    public function getPoints()
    {

        return Threat::where('threats.id', $this->id)
            ->join('analysis_questions', 'analysis_questions.threat_id', 'threats.id')
            ->join('recommendation_analysis_questions', 'recommendation_analysis_questions.analysis_question_id', 'analysis_questions.id')
            ->join('recommendations','recommendations.id','recommendation_analysis_questions.recommendation_id')
            ->sum('recommendations.points');
    }

    public function achievedPoints($user)
    {
        $totalPoints = 0;
        $customerRecommendations = $this->customerRecommendations($user)->whereStatus(CustomerRecommendation::$STATUS_COMPLETED)->get();

        foreach ($customerRecommendations as $rec) $totalPoints += $rec->recommendation->points ?? 0;

        return $totalPoints;
    }

    public function customerRecommendations($user)
    {
        $company = $user->company;
        return $this->hasMany(CustomerRecommendation::class)->where('company_id', $company->id);
    }

    public function employee()
    {
        return $this->belongsToMany(User::class, 'employee_threats', 'threat_id', 'user_id');
    }

    public function isImportant($company)
    {

        if ($this->is_always_important) return true;

        $companySize = $company->company_size;

        $industry = $company->industry_id;

        $importantCompanySize = $this->importantCompanySize ?? ['from' => 0, 'to' => 500];


        if ($this->important_if_company_size && $this->important_if_industry_id) {
            return ($importantCompanySize->from <= $companySize && $importantCompanySize->to >= $companySize)
                && count(array_intersect($this->importantIndustries->pluck('id')->toArray(), [$industry])) > 0;
        } 
        else if ($this->important_if_company_size_is)
            return $importantCompanySize->from <= $companySize && $importantCompanySize->to >= $companySize;

        else if ($this->important_if_industry_id)
            return count(array_intersect($this->importantIndustries->pluck('id')->toArray(), [$industry])) > 0;

        else
            return false;
    }

    public function customerThreat()
    {
        return $this->hasOne(AnalysisCustomerThreat::class)->where(function ($query) {
            if (auth()->user()) {
                $query->where('company_id', auth()->user()->company_id);
            }
        })->whereNull('restarted_at');
    }

    public function questionsForCustomers($user)
    {
        // $profile = $user->profile;
        $company = $user->company;

        $companySize = $company->company_size;

        $industry = $company->industry_id;

        $assets=$this->assetRelation()->pluck('solution_partner_product_id')->toArray();

        return $this->analysisQuestion()
            ->with('translations','analysisQuestionAnswer','analysisQuestionAnswer.media','companySize','customerAnswers')
            // ->notDeleted()
            ->havingCompanySize($companySize)
            ->havingAssets($assets)
            // ->automationConditions()
            ->relatedToIndustries([$industry]);
    }
}
