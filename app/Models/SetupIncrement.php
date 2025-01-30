<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetupIncrement extends Model
{
    use HasFactory;
    public $table = "setup_increment";
    protected $fillable = [
        'models',
        'last_number',
    ];
}
