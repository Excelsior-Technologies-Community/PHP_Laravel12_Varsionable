<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\VersionRestoreHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display all posts.
     */
    public function index(Request $request)
    {
        $query = Post::query();

        // Search posts
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $posts = $query
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store new post.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update post.
     *
     * Versionable automatically creates a new version.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated and new version created.');
    }

    /**
     * Display version history with search and date filters.
     */
    public function showVersions(Request $request, Post $post)
    {
        $query = $post->versions()
            ->orderBy('created_at', 'desc');

        /*
         * Date filtering.
         */
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        /*
         * Search inside version contents.
         *
         * contents is JSON, so we retrieve the versions
         * and perform a simple PHP search. This works
         * regardless of MySQL JSON configuration.
         */
        $versions = $query->get();

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $versions = $versions->filter(function ($version) use ($search) {
                $contents = is_array($version->contents)
                    ? $version->contents
                    : json_decode($version->contents, true);

                $title = strtolower($contents['title'] ?? '');
                $content = strtolower($contents['content'] ?? '');

                return str_contains($title, $search)
                    || str_contains($content, $search);
            })->values();
        }

        /*
         * Version statistics.
         */
        $totalVersions = $post->versions()->count();

        $latestVersion = $post->versions()
            ->latest('created_at')
            ->first();

        $firstVersion = $post->versions()
            ->oldest('created_at')
            ->first();

        return view('posts.versions', compact(
            'post',
            'versions',
            'totalVersions',
            'latestVersion',
            'firstVersion'
        ));
    }

    /**
     * Revert post to selected version.
     */
    public function revert($postId, $versionId)
    {
        $post = Post::findOrFail($postId);

        $version = $post->versions()
            ->where('id', $versionId)
            ->firstOrFail();

        /*
         * Restore selected version.
         */
        $post->revertToVersion($versionId);

        /*
         * Store restore activity.
         */
        VersionRestoreHistory::create([
            'post_id' => $post->id,
            'version_id' => $version->id,
            'post_title' => $post->title,
            'restored_at' => now(),
        ]);

        return redirect()
            ->route('posts.versions', $post)
            ->with('success', 'Post successfully restored to Version #' . $version->id);
    }

    /**
     * Version dashboard.
     */
    public function dashboard()
    {
        $totalPosts = Post::count();

        $totalVersions = DB::table('versions')
            ->where('versionable_type', Post::class)
            ->count();

        $totalRestores = VersionRestoreHistory::count();

        $postsWithVersions = Post::has('versions')->count();

        $averageVersions = $postsWithVersions > 0
            ? round($totalVersions / $postsWithVersions, 2)
            : 0;

        /*
         * Latest versions.
         */
        $latestVersions = Post::with('versions')
            ->has('versions')
            ->latest('updated_at')
            ->take(5)
            ->get();

        /*
         * Latest restore activities.
         */
        $restoreHistory = VersionRestoreHistory::with('post')
            ->latest('restored_at')
            ->take(10)
            ->get();

        /*
         * Most versioned posts.
         */
        $mostVersionedPosts = Post::withCount('versions')
            ->has('versions')
            ->orderByDesc('versions_count')
            ->take(5)
            ->get();

        return view('posts.dashboard', compact(
            'totalPosts',
            'totalVersions',
            'totalRestores',
            'postsWithVersions',
            'averageVersions',
            'latestVersions',
            'restoreHistory',
            'mostVersionedPosts'
        ));
    }
}