<?php

namespace App\Models;

use App\Traits\HasAsset;
use App\Traits\HasCompanySize;
use App\Traits\HasDisplayCondition;
use App\Traits\HasIndustry;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisQuestion extends Model
{
    use HasFactory, HasTranslations,HasIndustry, HasCompanySize, HasAsset, HasDisplayCondition;

    public const ANSWER_DROPDOWN = 'dropdown';
    public const ANSWER_MULTIPLE = 'multiple';
    public const ANSWER_MULTI_SELECT = 'multi_select';
    public const ANSWER_MULTIPLE_CHOICE = 'multiple_choice';
    public const ANSWER_RADIO = 'radio';
    public const ANSWER_SLIDER = 'slider';
    public const ANSWER_TEXT = 'text';

    protected $fillable=['question_type','details_level','video_link','show_if_industry','show_if_using_assets','show_if_company_size','display_if_conditions','automation_conditions','order','threat_id'];
    
    public function threat(){
        return $this->belongsTo(Threat::class);
    }
    
    public function recommendationQuestionAnswer(){
        return $this->belongsToMany(AnalysisQuestionAnswer::class, 'recommendation_question_answers', 'question_id','answer_id');
    }
    
    public function analysisQuestionAnswer(){
        return $this->hasMany(AnalysisQuestionAnswer::class);
    }

    public function analysisQuestionCondition(){
        return $this->hasMany(AnalysisQuestionCondition::class);
    }
    
    public function recommendation(){
        return $this->belongsToMany(Recommendation::class, 'recommendation_analysis_questions', 'analysis_question_id','recommendation_id');
    }
    
    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
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
    
    public function scopeHavingAssets($query, $assets = [])
    {
        return $query->where(function ($q) use ($assets) {
            $q->where('show_if_using_assets', 0)->orWhereHas('assetRelation', function ($q) use ($assets) {
                $q->whereIn('solution_partner_product_id', $assets);
            });
        });
    }
    
    public function scopeRelatedToIndustries($query, $industries = [])
    {
        return $query->where(function ($q) use ($industries) {
            $q->where('show_if_industry', 0)->orWhereHas('industryRelation', function ($q) use ($industries) {
                $q->whereIn('industry_id', $industries);
            });
        });
    }

    public function customerAnswers()
    {
        return $this->hasMany(AnalysisCustomerAnswer::class, 'question_id', 'id')->where('company_id', auth()->user()->company_id);
    }

}
