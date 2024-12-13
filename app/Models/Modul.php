<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;
    public $table = "modul";
    protected $fillable = [
        'judul',
        'hit',
        'original_name',
        'generated_name'
    ];
}
