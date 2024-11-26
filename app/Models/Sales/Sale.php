<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id']; 
    
    // Sale bir nechta SaleTovar'ga ega bo'ladi
    public function saleTovars()
    {
        return $this->hasMany(SaleTovar::class, 'unik_id', 'unik_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Sale bir Unik'ka tegishli
    public function unik()
    {
        return $this->belongsTo(Unik::class, 'unik_id');
    }
}
