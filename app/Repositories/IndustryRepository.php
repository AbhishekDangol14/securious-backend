<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryRepository extends BaseRepository
{
    public function __construct(Industry $industry)
    {
        $this->model = $industry;
    }
}
