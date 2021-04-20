<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Scrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol', 
        'name',
        'series',
        'bse_code',
        'isin_no',
        'listing_date',
        'group',
        'created_at',
        'updated_at'

    ];   
    public function getListingDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
