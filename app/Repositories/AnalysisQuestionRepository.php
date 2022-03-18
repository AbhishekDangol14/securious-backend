<?php

namespace App\Repositories;

use App\Models\AnalysisQuestion;

class AnalysisQuestionRepository extends BaseRepository
{
    public function __construct(AnalysisQuestion $analysisQuestion)
    {
        $this->model = $analysisQuestion;
    }
}
