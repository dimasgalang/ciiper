<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricMill extends Model
{
    use HasFactory;
    public $table = "fabric_mill";
    protected $fillable = [
        'fabmill_no',
        'fabmill_name',
    ];
}
