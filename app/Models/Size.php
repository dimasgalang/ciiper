<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    public $table = "size";
    protected $fillable = [
        'size_no',
        'size',
        'void'
    ];
}
