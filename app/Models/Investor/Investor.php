<?php

namespace App\Models\Investor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function investor_inputs()
    {
        return $this->hasOne(InvestorInput::class);
    }
    
}
