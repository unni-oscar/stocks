<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [        
        'name',
    ];   

    public function industries(){
        return $this->belongsToMany(Industry::class)->withTimestamps()->withPivot('id');    
    }
}
