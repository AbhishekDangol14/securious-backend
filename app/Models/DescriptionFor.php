<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptionFor extends Model
{
    use HasFactory;
protected $fillable=['recommendation_id'];
    public function descriptionForAsset(){
        return $this->belongsToMany(DescriptionForAsset::class,'description_for_assets','description_for_id','solution_partner_product_id');
    }
}
