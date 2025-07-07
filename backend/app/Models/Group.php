<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_private',
        'created_at',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_groups')
            ->withPivot('is_admin', 'is_moderator');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
