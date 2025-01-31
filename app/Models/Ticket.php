<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_id',
        'is_open',
        'subject',
        'message',
        'priority',
        'agent_id',
    ];

    public function getRouteKeyName()
    {
        return 'tracking_id'; 
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
