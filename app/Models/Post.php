<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelVersionable\Versionable;

class Post extends Model
{
    use Versionable;

    protected $fillable = [
        'title',
        'content',
    ];

    protected $versionable = [
        'title',
        'content',
    ];

    public function restoreHistories()
    {
        return $this->hasMany(VersionRestoreHistory::class);
    }
}