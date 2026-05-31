<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
        'ip_address', 'user_agent', 'bot_score', 'vpn_detected',
        'is_suspicious', 'security_flags', 'last_login_at', 'last_suspicious_activity_at'
    ];
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'bot_score' => 'float',
        'vpn_detected' => 'boolean',
        'is_suspicious' => 'boolean',
        'security_flags' => 'array',
        'last_login_at' => 'datetime',
        'last_suspicious_activity_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function graduate()
    {
        return $this->hasOne(Graduate::class);
    }
}