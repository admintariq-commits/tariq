<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareerResourceAccess extends Model
{
    use HasFactory;

    protected $table = 'career_resource_access';

    protected $fillable = [
        'graduate_id',
        'resource_id',
        'accessed_at',
        'completed',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
        'completed' => 'boolean',
        'rating' => 'integer',
    ];

    public function graduate()
    {
        return $this->belongsTo(Graduate::class);
    }

    public function resource()
    {
        return $this->belongsTo(CareerResource::class);
    }
}
