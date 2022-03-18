<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Plank\Mediable\Mediable;

class AnalysisQuestionAnswer extends Model
{
    use HasFactory,HasTranslations, Mediable;

    protected $fillable = ['solution_partner_product_id','analysis_question_id','order'];


}
