<?php

namespace App\Traits;

use App\Models\IndustryRelation;

trait HasIndustry
{
    public function industryRelation(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(IndustryRelation::class, 'related');
    }
}
