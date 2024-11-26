<?php

namespace App\Models\Sklad_in;

use App\Models\Valyuta;
use App\Models\Sklad\Madel;
use App\Models\Sklad_in\FakturaInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakturaTovar extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id']; // ID himoyalangan, boshqa ustunlar ommaviy to'ldiriladi

    // FakturaInput bilan bog'lanish (foreign key o'zgaruvchisi to'g'ri nomlangan bo'lishi kerak)
    public function FakturaInput()
    {
        return $this->belongsTo(FakturaInput::class, 'faktura_id'); // 'faktura_id' orqali bog'lanish
    }
    
    public function Madel()
    {
        return $this->belongsTo(Madel::class); // 'madel_id' orqali bog'lanish
    }

    // Valyuta bilan bog'lanish
    public function Valyuta()
    {
        return $this->belongsTo(Valyuta::class, 'valyuta_id');
    }
    
    
}
