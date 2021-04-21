<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bhavcopy extends Model
{
    use HasFactory;
    protected $fillable = [
        'bhavcopy_date',
        'prev_close', 
        'open_price',
        'high_price',
        'low_price',
        'last_price',
        'close_price',
        'avg_price',
        'trd_qty',
        'turnover',
        'no_of_trades',
        'del_qty',
        'del_per',
        'exchange',
        'scrip_id',
        'created_at',
        'updated_at'

    ];   
    public function scrip()
    {
        return $this->belongsTo(Scrip::class);
    }
}
