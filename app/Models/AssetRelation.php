<?php

namespace App\Models;

use App\Traits\HasAsset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRelation extends Model
{
    use HasFactory, HasAsset;

    protected $fillable=['related_id','related_model','solution_partner_product_id'];


}
