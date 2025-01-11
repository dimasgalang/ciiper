<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    public $table = "season";
    protected $fillable = [
        'season_no',
        'season_cat',
        'season_year',
    ];
}
