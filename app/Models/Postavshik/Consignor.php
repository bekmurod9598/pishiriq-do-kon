<?php

namespace App\Models\Postavshik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consignor extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
}
