<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMaster extends Model
{
    use HasFactory;
    public $table = "order_master";
    protected $fillable = [
        'order_trans',
        'season_no',
        'buyer_no',
        'brand_no',
        'po_no',
        'style_no',
        'qty_order',
        'qty_ofc',
        'qty_gmt',
        'qty_sbd',
        'fu_no',
        'wash_type',
        'remark',
        'sketch_file',
    ];
}
