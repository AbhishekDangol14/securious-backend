<?php

namespace App\Traits;

use App\Models\AssetRelation;

trait HasAsset {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function assetRelation(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(AssetRelation::class, 'related');
    }
}
