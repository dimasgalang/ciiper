<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabrication extends Model
{
    use HasFactory;
    public $table = "fabrication";
    protected $fillable = [
        'order_trans',
        'fab_no',
        'fabmill_no',
        'fabrication',
        'po_fab',
        'etd',
    ];
}
