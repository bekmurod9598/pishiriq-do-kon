<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentsms extends Model
{
    use HasFactory;
    protected $table = 'sentsms';  // 'sentsms' jadvali bilan ishlash
    protected $guarded = ['id'];
}
