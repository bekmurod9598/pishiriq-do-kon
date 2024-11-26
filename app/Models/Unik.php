<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sklad_out\SalesTovar;
use App\Models\Sales\Sale;

class Unik extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    // Unik bir nechta Sale'ga ega bo'lishi mumkin
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Unik bir nechta SaleTovar'ga ega bo'lishi mumkin
    public function saleTovars()
    {
        return $this->hasMany(SaleTovar::class);
    }
}
