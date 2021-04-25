<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


class IndustrySector extends Pivot
{
    use HasFactory;

    public function scrips()
    {
        return $this->hasMany(Scrip::class);
    }
    public function sector() {
        return $this->belongsTo(Sector::class);
    }

    public function industry() {
        return $this->belongsTo(Industry::class);
    }
    
}
