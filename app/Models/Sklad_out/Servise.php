<?php

namespace App\Models\Sklad_out;

use App\Models\Valyuta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servise extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function Valyuta()
    {
        return $this->belongsTo(Valyuta::class); // 'faktura_id' orqali bog'lanish
    }
}
