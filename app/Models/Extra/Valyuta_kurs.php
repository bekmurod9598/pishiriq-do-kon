<?php

namespace App\Models\Extra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Valyuta_kurs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'valyuta_days'; // Jadval nomi
    protected $guarded = ['id'];
}
