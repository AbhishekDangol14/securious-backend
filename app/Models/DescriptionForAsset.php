<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptionForAsset extends Model
{
    use HasFactory;

    public function descriptionFor(){
        return $this->belongsToMany(DescriptionFor::class);
    }
}
