<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCiiper extends Model
{
    use HasFactory;
    public $table = "log_ciiper";
    protected $fillable = [
        'username',
        'activity',
        'time',
        'icon',
        'color',
    ];
}
