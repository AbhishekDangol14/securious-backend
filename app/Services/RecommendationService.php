<?php

namespace App\Services;

use App\Repositories\RecommendationRepository;
use App\Traits\HasAssetService;
use App\Traits\HasCompanySizeService;
use App\Traits\HasDescriptionFor;
use App\Traits\HasIndustryService;
use App\Traits\HasQuestionAnswer;
use App\Traits\HasTranslationService;

class RecommendationService extends BaseService
{
    use HasTranslationService, HasIndustryService, HasCompanySizeService, HasAssetService, HasQuestionAnswer, HasDescriptionFor;

    public function __construct(RecommendationRepository $repository){
        $this->repository = $repository;
    }


}
