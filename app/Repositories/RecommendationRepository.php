<?php

namespace App\Repositories;

use App\Models\Recommendation;

class RecommendationRepository extends BaseRepository
{
    public function __construct(Recommendation $recommendation)
    {
        $this->model = $recommendation;
    }
}
