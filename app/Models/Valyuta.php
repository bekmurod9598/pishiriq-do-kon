<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Valyuta extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function Servise()
    {
        return $this->hasMany(Servise::class);
    }
    
    public function Madel()
    {
        return $this->hasMany(Madel::class);
    }
    
}

