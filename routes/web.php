<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Register resource routes for full CRUD operations on posts
Route::resource('posts', PostController::class);

// Route to display all versions of a specific post
Route::get('posts/{post}/versions', 
    [PostController::class, 'showVersions']
)->name('posts.versions');

// Route to revert a post to a specific version
Route::get('posts/{post}/revert/{version}', 
    [PostController::class, 'revert']
)->name('posts.revert');