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

    public function sectors(){
        return $this->belongsToMany(Sector::class)->withTimestamps()->withPivot('id');
    }
}
