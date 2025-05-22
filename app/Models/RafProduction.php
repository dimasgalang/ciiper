<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RafProduction extends Model
{
    use HasFactory;
    public $table = "raf_production";
    protected $fillable = [
        'order_trans',
        'order_list',
        'size_no',
        'raf_no',
        'raf_dept',
        'raf_date',
        'raf_qty',
        'carton_qty',
        'remark',
        'void'
    ];
}
