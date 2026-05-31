<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AlertType extends Model
{
    protected $fillable = ['name', 'months_threshold'];
    
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}