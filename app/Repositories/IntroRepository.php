<?php

namespace App\Repositories;

use App\Models\Company;

class IntroRepository extends BaseRepository
{
    public function __construct(Company $company)
    {
        $this->model = $company;
    }
}
