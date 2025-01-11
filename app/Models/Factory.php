<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    use HasFactory;
    public $table = "factory";
    protected $fillable = [
        'factory_no',
        'factory_name',
    ];
}
