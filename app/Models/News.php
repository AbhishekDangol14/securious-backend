<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class News extends Model
{
    use HasFactory, HasTranslations, Mediable;
    protected $fillable = ['is_active','news_category_id'];

    public function newsCategory(){
        $this->hasMany(NewsCategory::class);
    }
}
