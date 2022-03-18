<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisQuestionCondition extends Model
{
    protected $fillable=['analysis_question_id','question_id','answer_id','rule','is_equal_to'];
    use HasFactory;
}
