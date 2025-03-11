<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = ['user_id', 'ip_id', 'action', 'changes', "details", "ip_address"];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function () {
            return false;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
