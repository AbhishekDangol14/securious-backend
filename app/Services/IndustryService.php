<?php

namespace App\Services;


use App\Repositories\IndustryRepository;
use App\Traits\HasTranslationService;

class IndustryService extends BaseService
{
    use HasTranslationService;

    public function __construct(IndustryRepository $repository){
        $this->repository = $repository;
    }

}
