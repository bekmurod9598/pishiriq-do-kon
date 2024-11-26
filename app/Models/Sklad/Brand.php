<?php

namespace App\Models\Sklad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function madels()
    {
        return $this->hasMany(Madel::class);
    }
}
