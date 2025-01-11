<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderList extends Model
{
    use HasFactory;
    public $table = "order_list";
    protected $fillable = [
        'order_trans',
        'order_list',
        'factory_no',
        'lot_no',
        'pobuyer_no',
        'dcpo_qty',
        'ex_factory_date',
        'vsl_date',
        'status',
    ];
}
