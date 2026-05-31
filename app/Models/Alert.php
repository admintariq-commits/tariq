<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['graduate_id', 'alert_type_id', 'sent_at', 'status', 'response_message'];
    
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function graduate()
    {
        return $this->belongsTo(Graduate::class);
    }

    public function type()
    {
        return $this->belongsTo(AlertType::class, 'alert_type_id');
    }
}