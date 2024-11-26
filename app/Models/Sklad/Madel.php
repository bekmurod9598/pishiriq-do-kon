<?php

namespace App\Models\Sklad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Valyuta;

class Madel extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'madels';  // 'madels' jadvali bilan ishlash

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    
    public function valyuta()
    {
        return $this->belongsTo(Valyuta::class);
    }
    
    public function FakturaTovar()
    {
        return $this->hasMany(FakturaTovar::class);
    }
    
    
}
