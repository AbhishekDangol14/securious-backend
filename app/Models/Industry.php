<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['details_level', 'is_active'];

    public function threat(){
        return $this->belongsToMany(Threat::class);
    }

    public static function validations()
    {
        return [
            'details_level' => 'required'
        ];
    }
}
