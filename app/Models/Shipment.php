<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    public $table = "shipment";
    protected $fillable = [
        'ship_no',
        'order_list',
        'market_no',
        'shipmode_no',
        'size_no',
        'ship_qty',
        'carton_qty',
        'ship_date',
        'remark',
        'void'
    ];
}
