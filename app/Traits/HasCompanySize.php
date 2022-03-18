<?php

namespace App\Traits;

use App\Models\CompanySize;
use App\Models\IndustryRelation;

trait HasCompanySize
{
    public function companySize(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(CompanySize::class, 'related');
    }
}
