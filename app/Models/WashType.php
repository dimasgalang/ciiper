<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WashType extends Model
{
    use HasFactory;
    public $table = "wash_type";
    protected $fillable = [
        'wash_no',
        'wash_type',
    ];
}
