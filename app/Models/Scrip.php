<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol', 
        'series',
        'bse_code',
        'isin_no',
        'group',
        'created_at',
        'updated_at'

    ];   
}
