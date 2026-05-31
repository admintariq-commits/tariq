<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name', 'code', 'unemployment_rate_factor'];

    public function universities()
    {
        return $this->hasMany(University::class);
    }

    public function graduates()
    {
        return $this->hasManyThrough(Graduate::class, University::class);
    }
}