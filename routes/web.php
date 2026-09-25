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
| Post CRUD
|--------------------------------------------------------------------------
*/

Route::resource('posts', PostController::class);

/*
|--------------------------------------------------------------------------
| Version History
|--------------------------------------------------------------------------
*/

Route::get('posts/{post}/versions', [
    PostController::class,
    'showVersions'
])->name('posts.versions');

/*
|--------------------------------------------------------------------------
| Revert Version
|--------------------------------------------------------------------------
*/

Route::post('posts/{post}/revert/{version}', [
    PostController::class,
    'revert'
])->name('posts.revert');