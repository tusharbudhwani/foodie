<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // RELATIONSHIPS

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function owner()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function getImagePathAttribute()
    {
        return 'storage/' .$this->image;
    }
}
