<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['graduate_id', 'job_market_id', 'status', 'cover_letter'];
    
    public function graduate()
    {
        return $this->belongsTo(Graduate::class);
    }
    
    public function job()
    {
        return $this->belongsTo(JobMarket::class, 'job_market_id');
    }
}