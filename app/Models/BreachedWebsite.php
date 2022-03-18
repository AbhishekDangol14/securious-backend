<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreachedWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'logo',
        'date',
    ];

    public function classes(){
        return $this->belongsToMany(BreachedClass::class,'breached_website_classes','website_id','class_id');
    }
}
