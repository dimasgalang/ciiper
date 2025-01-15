<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    public $table = "shipment";
    protected $fillable = [
        'order_list',
        'market_no',
        'ship_no',
        'ship_qty',
        'ship_date',
        'remark',
    ];
}
