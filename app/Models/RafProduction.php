<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RafProduction extends Model
{
    use HasFactory;
    public $table = "raf_production";
    protected $fillable = [
        'raf_no',
        'order_list',
        'raf_date',
        'raf_qty',
        'remark',
    ];
}
