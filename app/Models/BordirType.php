<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BordirType extends Model
{
    use HasFactory;
    public $table = "bordir_type";
    protected $fillable = [
        'bordir_no',
        'bordir_type',
        'void',
    ];
}
