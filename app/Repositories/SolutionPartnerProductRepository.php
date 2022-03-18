<?php

namespace App\Repositories;
use App\Models\SolutionPartnerProduct;

class SolutionPartnerProductRepository extends BaseRepository
{
    public function __construct(SolutionPartnerProduct $solutionPartnerProduct)
    {
        $this->model = $solutionPartnerProduct;
    }
}
