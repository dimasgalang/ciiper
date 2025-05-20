<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProPlanAcc extends Model
{
    use HasFactory;
    public $table = "proplan_acc";
    protected $fillable = [
        'proplan_acc_no',
        'category_no',
        'order_trans',
        'order_list',
        'accesories_no',
        'item_date',
        'qty',
        'void',
    ];
}
