<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Plank\Mediable\Mediable;

class SolutionPartner extends Model
{
    use HasFactory, HasTranslations, Mediable;

    protected $fillable = [
        'is_active',
        'status'
    ];

    // public function translation(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(SolutionPartnerTranslation::class);
    // }

    public function solutionPartnerProducts(){
        return $this->hasMany(SolutionPartnerProduct::class);
    }
}
