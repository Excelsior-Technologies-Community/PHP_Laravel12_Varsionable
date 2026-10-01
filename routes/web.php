<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('posts.index');
});

/*
|--------------------------------------------------------------------------
| Versionable Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/version-dashboard', [
    PostController::class,
    'dashboard'
])->name('posts.dashboard');

/*
|--------------------------------------------------------------------------
| Post Export & Bulk Actions
|--------------------------------------------------------------------------
*/

Route::get('/posts-export', [
    PostController::class,
    'exportPosts'
])->name('posts.export');

Route::delete('/posts-bulk-delete', [
    PostController::class,
    'bulkDestroy'
])->name('posts.bulkDestroy');

Route::post('/posts/{post}/duplicate', [
    PostController::class,
    'duplicate'
])->name('posts.duplicate');

/*
|--------------------------------------------------------------------------
| Side-by-Side Visual Diff Inspector
|--------------------------------------------------------------------------
*/

Route::get('/posts/{post}/versions/compare', [
    PostController::class,
    'compareVersions'
])->name('posts.versions.compare');

/*
|--------------------------------------------------------------------------
| Version Custom Milestones & Lock Manager
|--------------------------------------------------------------------------
*/

Route::post('/posts/{post}/versions/{version}/tag', [
    PostController::class,
    'tagVersion'
])->name('posts.version.tag');

Route::post('/posts/{post}/versions/{version}/lock', [
    PostController::class,
    'toggleLockVersion'
])->name('posts.version.lock');

/*
|--------------------------------------------------------------------------
| Selective Field-Level Restore (Partial Rollback)
|--------------------------------------------------------------------------
*/

Route::post('/posts/{post}/revert-selective/{version}', [
    PostController::class,
    'revertSelective'
])->name('posts.revert-selective');

/*
|--------------------------------------------------------------------------
| Version Export & History
|--------------------------------------------------------------------------
*/

Route::get('/posts/{post}/versions/export', [
    PostController::class,
    'exportVersions'
])->name('posts.versions.export');

Route::get('/posts/{post}/versions/{version}/json', [
    PostController::class,
    'exportVersionJson'
])->name('posts.version.json');

Route::get('/posts/{post}/versions', [
    PostController::class,
    'showVersions'
])->name('posts.versions');

Route::post('/posts/{post}/revert/{version}', [
    PostController::class,
    'revert'
])->name('posts.revert');

/*
|--------------------------------------------------------------------------
| Post CRUD
|--------------------------------------------------------------------------
*/

Route::resource('posts', PostController::class);