<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDept extends Model
{
    use HasFactory;
    public $table = "production_dept";
    protected $fillable = [
        'dept_no',
        'dept_name',
        'void',
    ];
}
