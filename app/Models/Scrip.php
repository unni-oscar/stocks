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
        'status',
        // 'sector_pe',
        // 'symbol_pe',
        'trading_status',
        'issuedCap',
        'bse_code',
        'isin_no',
        'listing_date',
        'group',
        'faceValue',
        'created_at',
        'updated_at',

    ];   

    public function getListingDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function industry_sector()
    {
        return $this->belongsTo(IndustrySector::class);
    }
    // public function sector()
    // {
    //     return $this->belongsTo(Sector::class);
    // }
    public function bhavcopy()
    {
        return $this->hasMany(Bhavcopy::class);
    }
}
