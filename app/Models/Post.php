<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelVersionable\Versionable;

class Post extends Model
{
    use Versionable;

    protected $fillable = ['title', 'content'];

    // Only these fields will be versioned
    protected $versionable = ['title', 'content'];
}