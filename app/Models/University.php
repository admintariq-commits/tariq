<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = ['name', 'region_id', 'ranking'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function graduates()
    {
        return $this->hasMany(Graduate::class);
    }
}