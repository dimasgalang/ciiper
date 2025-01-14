<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;
    public $table = "followup";
    protected $fillable = [
        'fu_no',
        'fu_name',
    ];
}
