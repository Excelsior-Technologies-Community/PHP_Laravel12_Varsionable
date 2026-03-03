#  PHP_Laravel12_Varsionable

<p align="center">
<a href="#"><img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel Version"></a>
<a href="#"><img src="https://img.shields.io/badge/PHP-8.2+-blue" alt="PHP Version"></a>
<a href="#"><img src="https://img.shields.io/badge/Package-laravel--versionable-green" alt="Versionable Package"></a>
<a href="#"><img src="https://img.shields.io/badge/Database-MySQL-orange" alt="Database"></a>
</p>


---

##  Overview

**PHP_Laravel12_Versionable** is a Laravel 12 project that demonstrates how to implement a complete model version control system using the `overtrue/laravel-versionable` package.

This project allows you to:

* Automatically track changes to model fields
* View complete version history
* Compare old and new values
* Revert to any previous version
* Maintain full audit history without deleting records

It is ideal for blog systems, CMS platforms, admin panels, and any system requiring data revision tracking.

---

##  Features

*  Full CRUD for Posts
*  Automatic Version Tracking
*  Version History Page
*  Field-level Change Comparison
*  Safe Revert Functionality
*  No Data Loss
*  Clean Blade UI

---

##  Folder Structure

```
app/
 ├── Models/
 │     └── Post.php
 ├── Http/Controllers/
 │     └── PostController.php

database/
 └── migrations/
       └── create_posts_table.php

resources/
 └── views/posts/
       ├── index.blade.php
       ├── create.blade.php
       ├── edit.blade.php
       └── versions.blade.php

routes/
 └── web.php
```

---

## STEP 1 — Create Laravel Project

```bash
composer create-project laravel/laravel laravel-versionable-app
```

---

## STEP 2 — Database Setup (.env)

Open `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## STEP 3 — Install Versionable Package

```bash
composer require overtrue/laravel-versionable
```

```bash
php artisan vendor:publish --provider="Overtrue\LaravelVersionable\ServiceProvider"
```

```bash
php artisan migrate
```

---

## STEP 4 — Create Post Model + Migration

Create:

```bash
php artisan make:model Post -m
```

### database/migrations/xxxx_create_posts_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

Run:

```bash
php artisan migrate
```

---

## STEP 5 — Post Model

### app/Models/Post.php

```php
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
```

---

## STEP 6 — Controller

Create:

```bash
php artisan make:controller PostController
```

### app/Http/Controllers/PostController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        Post::create($request->all());
        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $post->update($request->all());
        return redirect()->route('posts.index');
    }

    public function showVersions(Post $post)
    {
        $versions = $post->versions;
        return view('posts.versions', compact('post', 'versions'));
    }

    public function revert($postId, $versionId)
    {
        $post = Post::findOrFail($postId);
        $post->revertToVersion($versionId);
        return redirect()->back();
    }
}
```

---

## STEP 7 — Routes

### routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class);

Route::get('posts/{post}/versions',
    [PostController::class, 'showVersions']
)->name('posts.versions');

Route::get('posts/{post}/revert/{version}',
    [PostController::class, 'revert']
)->name('posts.revert');
```

---

## STEP 8 — Views Folder

Create folder:

```
resources/views/posts/
```

---

### 1. resources/views/posts/index.blade.php

```html
<!DOCTYPE html>
<html>
<head>
<title>Posts</title>
<style>
body { font-family: Arial; background: #f4f6f9; margin:0; padding:30px; }
.container { max-width: 900px; margin:auto; }
.card { background:white; padding:20px; margin-bottom:20px; border-radius:8px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
.btn { padding:8px 15px; text-decoration:none; border-radius:5px; font-size:14px; }
.btn-primary { background:#3498db; color:white; }
.btn-warning { background:#f39c12; color:white; }
.btn-info { background:#2ecc71; color:white; }
h1 { margin-bottom:20px; }
</style>
</head>
<body>

<div class="container">
<h1> All Posts</h1>

<a href="{{ route('posts.create') }}" class="btn btn-primary">+ Create Post</a>

@foreach($posts as $post)
<div class="card">
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->content }}</p>

    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('posts.versions', $post) }}" class="btn btn-info">Versions</a>
</div>
@endforeach

</div>
</body>
</html>
```

---

### 2. resources/views/posts/create.blade.php

```html
<!DOCTYPE html>
<html>
<head>
<title>Create Post</title>
<style>
body { font-family: Arial; background:#eef2f7; padding:40px; }
.form-box { background:white; padding:30px; max-width:600px; margin:auto; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
input, textarea { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; }
button { background:#3498db; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; }
</style>
</head>
<body>

<div class="form-box">
<h2>Create Post</h2>

<form method="POST" action="{{ route('posts.store') }}">
@csrf
<input type="text" name="title" placeholder="Enter Title">
<textarea name="content" placeholder="Enter Content"></textarea>
<button type="submit">Save</button>
</form>

</div>
</body>
</html>
```

---

### 3. resources/views/posts/edit.blade.php

```html
<!DOCTYPE html>
<html>
<head>
<title>Edit Post</title>
<style>
body { font-family: Arial; background:#f4f6f9; padding:40px; }
.form-box { background:white; padding:30px; max-width:600px; margin:auto; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
input, textarea { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; }
button { background:#e67e22; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; }
</style>
</head>
<body>

<div class="form-box">
<h2>Edit Post</h2>

<form method="POST" action="{{ route('posts.update', $post) }}">
@csrf
@method('PUT')

<input type="text" name="title" value="{{ $post->title }}">
<textarea name="content">{{ $post->content }}</textarea>

<button type="submit">Update</button>
</form>

</div>
</body>
</html>
```

---

### 4. resources/views/posts/versions.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Versions</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 950px;
            margin: auto;
        }

        .version-card {
            background: #ffffff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .btn {
            padding: 7px 15px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 15px;
        }

        .btn:hover {
            background: #c0392b;
        }

        .diff-box {
            background: #f9fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #3498db;
        }

        .field {
            margin-bottom: 12px;
        }

        .field-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .old {
            color: #e74c3c;
            font-weight: bold;
        }

        .new {
            color: #27ae60;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Versions of "{{ $post->title }}"</h2>

    @foreach($versions as $index => $version)

        @php
            $currentData = is_array($version->contents)
                ? $version->contents
                : json_decode($version->contents, true);

            $previousData = null;

            if($index > 0){
                $prevContents = $versions[$index - 1]->contents;

                $previousData = is_array($prevContents)
                    ? $prevContents
                    : json_decode($prevContents, true);
            }
        @endphp

        <div class="version-card">

            <p><strong>Version ID:</strong> {{ $version->id }}</p>
            <p><strong>Created:</strong> {{ $version->created_at }}</p>

            @if($previousData)
                <div class="diff-box">
                    <h4>Changed Fields:</h4>

                    @foreach($currentData as $field => $value)

                        @php
                            $oldValue = $previousData[$field] ?? null;
                        @endphp

                        @if($oldValue != $value)

                            <div class="field">
                                <div class="field-title">{{ ucfirst($field) }}</div>

                                <div>
                                    <span class="old">Old:</span>
                                    {{ $oldValue ?? '—' }}
                                </div>

                                <div>
                                    <span class="new">New:</span>
                                    {{ $value ?? '—' }}
                                </div>
                            </div>

                        @endif

                    @endforeach

                </div>
            @endif

            <a href="{{ route('posts.revert', [$post->id, $version->id]) }}" class="btn">
                Revert
            </a>

        </div>

    @endforeach

</div>

</body>
</html>
```

---

# OUTPUT

## 1. Home Page – All Posts

URL:

```
http://127.0.0.1:8000/posts
```
<img width="844" height="396" alt="Screenshot 2026-03-03 165416" src="https://github.com/user-attachments/assets/a58f0ea7-c640-4e9e-a29c-1de90ff3a73d" />

---
## 2. Create Post Page

URL:

```
http://127.0.0.1:8000/posts/create
```
<img width="656" height="285" alt="Screenshot 2026-03-03 164121" src="https://github.com/user-attachments/assets/25c14c19-3b9d-4cd8-95c7-580aa43cb382" />

---
## 3. Edit Post Page

URL:

```
http://127.0.0.1:8000/posts/{id}/edit
```
<img width="658" height="288" alt="Screenshot 2026-03-03 164158" src="https://github.com/user-attachments/assets/7e6ee861-556a-4ce6-a9c4-3b6a05911190" />

---
## 4. Versions Page

URL:

```
http://127.0.0.1:8000/posts/{id}/versions
```

Each version card displays:

* Version ID
* Created date and time
* Changed fields
* Old value (Red color)
* New value (Green color)
* Revert button

---
<img width="892" height="873" alt="Screenshot 2026-03-03 165308" src="https://github.com/user-attachments/assets/360d3a87-f87e-475d-9650-65dbfa4b6231" />

---
## 5. Revert Functionality

When the user clicks the Revert button:

* The selected version data becomes the current post data
* A new version entry is created automatically
* No previous version is deleted
* Full version history remains intact

This ensures complete data integrity and safe rollback functionality.

---
<img width="891" height="556" alt="Screenshot 2026-03-03 165440" src="https://github.com/user-attachments/assets/1015e998-0aa8-419a-9a57-47c1fb53602a" />

---
## 6. Database Output

<img width="1183" height="345" alt="Screenshot 2026-03-03 165502" src="https://github.com/user-attachments/assets/473eaacf-0049-4f18-9f07-cb4203beeb5b" />

---
