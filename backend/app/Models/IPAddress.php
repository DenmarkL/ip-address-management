<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IPAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ip_address', 'label', 'comment'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
