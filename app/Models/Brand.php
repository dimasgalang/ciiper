<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    public $table = "brand";
    protected $fillable = [
        'buyer_no',
        'brand_no',
        'brand_name',
        'brand_gender',
    ];
}
