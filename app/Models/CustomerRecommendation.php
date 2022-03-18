<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRecommendation extends Model
{
    use HasFactory;

    static $STATUS_IGNORED = 'ignored';
    static $STATUS_COMPLETED = 'completed';
    static $STATUS_IN_PROGRESS = 'in_progress';
    static $NOT_STARTED = 'not_started';
}
