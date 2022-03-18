<?php

namespace App\Repositories;

use App\Models\SolutionPartner;

class SolutionPartnerRepository extends BaseRepository
{
    public function __construct(SolutionPartner $solutionPartner)
    {
        $this->model = $solutionPartner;
    }
}
