<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSize extends Model
{
    use HasFactory;
    public $table = "order_size";
    protected $fillable = [
        'order_size_no',
        'order_list',
        'order_trans',
        'size_no',
        'qty',
        'void',
    ];
}
