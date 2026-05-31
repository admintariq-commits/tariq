<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'level'];

    public function graduates()
    {
        return $this->hasMany(Graduate::class);
    }
}