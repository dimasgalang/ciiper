<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accesories extends Model
{
    use HasFactory;
    public $table = "accesories";
    protected $fillable = [
        'accesories_no',
        'category_no',
        'accesories_name',
        'void',
    ];
}
