<?php

namespace App\Repositories;

use App\Models\Threat;

class ThreatRepository extends BaseRepository
{
    public function __construct(Threat $threat)
    {
        $this->model = $threat;
    }
}
