<?php

namespace App\Services;


use App\Repositories\SolutionPartnerProductRepository;
use App\Traits\HasTranslationService;
use App\Traits\HasIndustryService;
use App\Traits\HasCompanySizeService;

class SolutionPartnerProductService extends BaseService
{
    use HasTranslationService, HasIndustryService, HasCompanySizeService;

    public function __construct(SolutionPartnerProductRepository $repository){
        $this->repository = $repository;
    }
}
