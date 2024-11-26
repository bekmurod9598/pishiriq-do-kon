<?php

namespace App\Models\Sklad_in;

use App\Models\Valyuta;
use App\Models\Postavshik\Consignor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakturaInput extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = array('id');

    public function consignor()
    {
        return $this->belongsTo(Consignor::class, 'consignor_id'); // 'cosignor_id' ustuni orqali bog'lanish
    }

    // Valyuta bilan bog'lanish (belongsTo)
    public function valyuta()
    {
        return $this->belongsTo(Valyuta::class, 'valyuta_id'); // 'valyuta_id' ustuni orqali bog'lanish
    }
}
