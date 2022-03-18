<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreatNeutralized extends Model
{
    use HasFactory;
    public const STATUS_NEEDS_RECHECK = 1;
    public const STATUS_DOESNOT_NEED_RECHECK = 0;
    protected $fillable = ['user_id', 'recheck_status', 'threats_id', 'customer_threat_id', 'company_id'];
}
