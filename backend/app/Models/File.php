<?php

namespace App\Models;

use App\Models\Message\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    protected $fillable = [
        'title',
        'file_url',
        'file_type',
        'file_size'
    ];

    protected $casts = [
        'file_size' => 'double'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_files')
            ->withPivot('file_type');
    }
}
