<?php

namespace App\Models\Kassa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function costs()
    {
        return $this->hasMany(CostType::class);
    }
}
