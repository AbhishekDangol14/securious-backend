<?php

namespace App\Services;


use App\Repositories\NewsRepository;
use App\Traits\HasTranslationService;

class NewsService extends BaseService
{
    use HasTranslationService;

    public function __construct(NewsRepository $repository){
        $this->repository = $repository;
    }

}
