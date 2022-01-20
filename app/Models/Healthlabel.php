<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Healthlabel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}
