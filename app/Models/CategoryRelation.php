<?php

namespace App\Models;

use App\Traits\HasCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRelation extends Model
{
    protected $fillable=['category_id', 'related_id','related_model'];

    use HasFactory, HasCategory;
}
