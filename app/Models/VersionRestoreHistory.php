<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VersionRestoreHistory extends Model
{
    protected $fillable = [
        'post_id',
        'version_id',
        'post_title',
        'restored_at',
    ];

    protected $casts = [
        'restored_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}