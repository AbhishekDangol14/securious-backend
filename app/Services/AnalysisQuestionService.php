<?php

namespace App\Services;

use App\Repositories\AnalysisQuestionRepository;
use App\Traits\HasAssetService;
use App\Traits\HasCompanySizeService;
use App\Traits\HasDisplayCondition;
use App\Traits\HasIndustryService;
use App\Traits\HasTranslationService;
use App\Traits\HasAnswerService;

class AnalysisQuestionService extends BaseService
{
    use HasTranslationService, HasAssetService, HasCompanySizeService, HasIndustryService, HasDisplayCondition, HasAnswerService;

    public function __construct(AnalysisQuestionRepository $repository){
        $this->repository = $repository;
    }

}
