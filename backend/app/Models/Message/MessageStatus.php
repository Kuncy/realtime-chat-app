<?php

namespace App\Models\Message;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageStatus extends Model
{
    protected $table = 'message_status';

    protected $fillable = [
        'message_id',
        'user_id',
        'is_delivered',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_delivered' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
