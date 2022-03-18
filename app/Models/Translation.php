<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{

    protected $fillable = [
        'related_id',
        'related_type',
        'attribute_name',
        'attribute_value',
        'language'
    ];
    use HasFactory;

    public function translation(){
        return $this->morphToMany();
    }
}
