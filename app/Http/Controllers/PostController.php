<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Display all posts ordered by latest first
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    // Show the form to create a new post
    public function create()
    {
        return view('posts.create');
    }

    // Store a newly created post in the database
    public function store(Request $request)
    {
        Post::create($request->all());
        return redirect()->route('posts.index');
    }

    // Show the form to edit an existing post
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Update the selected post and automatically create a version
    public function update(Request $request, Post $post)
    {
        $post->update($request->all()); 
        return redirect()->route('posts.index');
    }

    // Display all versions of the selected post
    public function showVersions(Post $post)
    {
        $versions = $post->versions;
        return view('posts.versions', compact('post', 'versions'));
    }

    // Revert the post to a specific previous version
    public function revert($postId, $versionId)
    {
        $post = Post::findOrFail($postId);
        $post->revertToVersion($versionId);
        return redirect()->back();
    }
}