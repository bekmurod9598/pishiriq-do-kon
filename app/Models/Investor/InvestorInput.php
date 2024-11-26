<?php

namespace App\Models\Investor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestorInput extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'investor_inputs';  // 'madels' jadvali bilan ishlash
    
    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }
}
