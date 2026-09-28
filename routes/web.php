<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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
| Post Export
|--------------------------------------------------------------------------
*/

Route::get('/posts-export', [
    PostController::class,
    'exportPosts'
])->name('posts.export');

/*
|--------------------------------------------------------------------------
| Bulk Delete
|--------------------------------------------------------------------------
*/

Route::delete('/posts-bulk-delete', [
    PostController::class,
    'bulkDestroy'
])->name('posts.bulkDestroy');

/*
|--------------------------------------------------------------------------
| Duplicate
|--------------------------------------------------------------------------
*/

Route::post('/posts/{post}/duplicate', [
    PostController::class,
    'duplicate'
])->name('posts.duplicate');

/*
|--------------------------------------------------------------------------
| Version CSV Export
|--------------------------------------------------------------------------
*/

Route::get('/posts/{post}/versions/export', [
    PostController::class,
    'exportVersions'
])->name('posts.versions.export');

/*
|--------------------------------------------------------------------------
| Individual Version JSON
|--------------------------------------------------------------------------
*/

Route::get('/posts/{post}/versions/{version}/json', [
    PostController::class,
    'exportVersionJson'
])->name('posts.version.json');

/*
|--------------------------------------------------------------------------
| Version History
|--------------------------------------------------------------------------
*/

Route::get('/posts/{post}/versions', [
    PostController::class,
    'showVersions'
])->name('posts.versions');

/*
|--------------------------------------------------------------------------
| Revert Version
|--------------------------------------------------------------------------
*/

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