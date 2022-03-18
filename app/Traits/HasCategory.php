<?php

namespace App\Traits;
use App\Models\CategoryRelation;

trait HasCategory{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */

    public function categoryRelation(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(CategoryRelation::class, 'related');
    }

}
