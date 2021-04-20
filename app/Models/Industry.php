<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;
    protected $fillable = [        
        'name',
    ];   

    public function scripts()
    {
        return $this->hasMany(Scrip::class);
    }
}
