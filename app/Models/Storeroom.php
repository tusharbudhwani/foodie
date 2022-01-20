<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storeroom extends Model
{
    use HasFactory;

    protected $table = "storeroom";


    const PURCHASED = 1;
    const NOT_PURCHASED = 0;

    const FAVOURITE = 1;
    const NOT_FAVOURITE = 0;

    const CONSUMED = 1;
    const NOT_CONSUMED = 0;

    // SCOPES
    public function scopeIsNotPurchased($query)
    {
        return $query->where('isPurchased', self::NOT_PURCHASED);
    }
    public function scopeIsPurchased($query)
    {
        return $query->where('isPurchased', self::PURCHASED);
    }
    public function scopeIsNotConsumed($query)
    {
        return $query->where('isConsumed', self::NOT_CONSUMED);
    }
    public function scopeIsConsumed($query)
    {
        return $query->where('isConsumed', self::CONSUMED);
    }

    public function getExpiryAttribute()
    {
        $expiry = new Carbon($this->expiry_date);
        $now = Carbon::now();

        return ($expiry->diff($now)->days < 1) ? 'today' : $expiry->diffForHumans($now);
    }

    public function expiryProductsWithinAWeek()
    {
        $expiry = new Carbon($this->expiry_date);
        $now = Carbon::now();

        return ($expiry->diff($now)->days < 7) ? true : false;
    }
    public function scopeIsNotFavourite($query)
    {
        return $query->where('isFavourite', self::NOT_FAVOURITE);
    }
    public function scopeIsFavourite($query)
    {
        return $query->where('isFavourite', self::FAVOURITE);
    }

    protected $guarded = ['id'];

    public function owner(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function unit(){
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
}
