<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationAnalysisQuestion extends Model
{
    use HasFactory;

    public function recommendationQuestionAnswer(){
        return $this->belongsToMany(AnalysisQuestionAnswer::class, 'recommendation_question_answers', 'question_id','answer_id');
    }
}
