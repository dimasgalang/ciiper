<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlanning extends Model
{
    use HasFactory;
    public $table = "production_planning";
    protected $fillable = [
        'plan_no',
        'order_trans',
        'order_list',
        'has_sample',
        'has_mi',
        'has_cart',
        'fab_date',
        'acc_date',
        'bordir_approve',
        'pattern_date',
        'sampletest_date',
        'marker_date',
        'pilotrun_date',
        'ppm_date',
        'startcut_date',
        'finishcut_date',
        'startsew_date',
        'finishsew_date',
        'finishpack_date',
        'remark',
    ];
}
