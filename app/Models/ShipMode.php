<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipMode extends Model
{
    use HasFactory;
    public $table = "ship_mode";
    protected $fillable = [
        'ship_no',
        'ship_name',
    ];
}
