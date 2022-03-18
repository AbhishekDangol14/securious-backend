<?php

namespace App\Models;

use App\Traits\HasAsset;
use App\Traits\HasCompanySize;
use App\Traits\HasIndustry;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory,HasTranslations, HasIndustry, HasCompanySize, HasAsset;

    protected $fillable=['is_automated','points','show_if_industry','show_if_company_size','threat_id','display_if_conditions','order'];

    public function analysisQuestion(){
        return $this->belongsToMany(AnalysisQuestion::class, 'recommendation_analysis_questions', 'recommendation_id','analysis_question_id');
    }
    public function solutionpartner(){
        return $this->belongsToMany(SolutionPartner::class, 'recommendation_partners', 'recommendation_id','solution_partner_id');
    }
    public function recommendationQuestionAnswer(){
        return $this->hasMany(RecommendationQuestionAnswer::class);
    }
    public function threat(){
        return $this->belongsTo(Threat::class);
    }
    public function descriptionFor(){
        return $this->hasMany(DescriptionFor::class);
    }
}
