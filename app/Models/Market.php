<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;
    public $table = "market";
    protected $fillable = [
        'market_no',
        'market_name',
    ];
}
