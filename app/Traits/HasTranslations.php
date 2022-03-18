<?php

namespace App\Traits;

use App\Models\Translation;

trait HasTranslations {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Translation::class, 'related');
    }
}
