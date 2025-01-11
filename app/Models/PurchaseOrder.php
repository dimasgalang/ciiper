<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    public $table = "purchase_order";
    protected $fillable = [
        'po_no',
        'po_desc',
    ];
}
