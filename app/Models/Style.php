<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;
    public $table = "style";
    protected $fillable = [
        'brand_no',
        'style_no',
        'style_name',
    ];
}
