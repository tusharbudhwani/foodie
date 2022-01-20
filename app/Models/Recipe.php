<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    //GETTERS
    public function getImagePathAttribute()
    {
        return 'storage/' .$this->image;
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // SCOPES
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }
    public function scopeNotPublished($query)
    {
        return $query->where('published_at', '=', null)->orWhere('published_at', '>=', now());
    }
    //RELATIONSHIPS
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function healthlabels()
    {
        return $this->belongsToMany(Healthlabel::class)->withTimestamps();
    }
}
