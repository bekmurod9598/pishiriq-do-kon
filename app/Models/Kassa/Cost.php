<?php

namespace App\Models\Kassa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'costs';  // 'madels' jadvali bilan ishlash
    
    public function costType()
    {
        return $this->belongsTo(CostType::class, 'type_id');
    }
}
