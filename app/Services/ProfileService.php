<?php

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService extends BaseService
{
    public function __construct(ProfileRepository $repository){
        $this->repository = $repository;
    }
}
