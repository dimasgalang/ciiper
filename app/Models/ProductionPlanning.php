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
        'sample_date',
        'has_mi',
        'mi_date',
        'has_fab_cart',
        'fab_date',
        'has_acc_cart',
        'acc_date',
        'bordir_approve',
        'pattern_date',
        'sampletest_date',
        'reqmarker_date',
        'marker_date',
        'pilotrun_date',
        'ppm_date',
        'startcut_date',
        'finishcut_date',
        'startsew_date',
        'finishsew_date',
        'finishpack_date',
        'remark',
        'void',
    ];
}
