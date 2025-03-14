<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlanDetail extends Model
{
    use HasFactory;
    public $table = "proplan_detail";
    protected $fillable = [
        'proplan_no',
        'order_trans',
        'order_list',
        'item',
        'category_no',
        'percentage',
        'remark',
        'status',
        'void',
    ];
}
