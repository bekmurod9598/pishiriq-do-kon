<?php

namespace App\Models\Sklad_out;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Sklad\Madel;

class SalesTovar extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id']; 
    
     // SaleTovar bir Sale'ga tegishli bo'ladi
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'unik_id', 'unik_id');
    }
    
    public function Madel()
    {
        return $this->belongsTo(Madel::class, 'madel_id');
    }

    // SaleTovar bir Unik'ka tegishli
    public function unik()
    {
        return $this->belongsTo(Unik::class, 'unik_id');
    }

}
