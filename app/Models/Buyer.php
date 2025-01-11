<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;
    public $table = "buyer";
    protected $fillable = [
        'buyer_no',
        'buyer_name',
    ];
}
