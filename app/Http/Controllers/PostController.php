<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\VersionRestoreHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostController extends Controller
{
    /**
     * Display all posts.
     *
     * Added:
     * - Search
     * - Sorting
     * - Pagination
     * - Version count filtering
     * - Listing statistics
     */
    public function index(Request $request)
    {
        $query = Post::query()
            ->withCount('versions');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Version Count Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('min_versions')) {
            $minVersions = max(0, (int) $request->min_versions);

            $query->having('versions_count', '>=', $minVersions);
        }

        if ($request->filled('max_versions')) {
            $maxVersions = max(0, (int) $request->max_versions);

            $query->having('versions_count', '<=', $maxVersions);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        $allowedSorts = [
            'id',
            'title',
            'created_at',
            'updated_at',
            'versions_count',
        ];

        $sort = $request->get('sort', 'created_at');

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $direction = strtolower($request->get('direction', 'desc'));

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $query->orderBy($sort, $direction);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $posts = $query
            ->paginate(8)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Listing Statistics
        |--------------------------------------------------------------------------
        */
        $totalPosts = Post::count();

        $totalVersions = DB::table('versions')
            ->where('versionable_type', Post::class)
            ->count();

        $postsWithVersions = Post::has('versions')->count();

        $averageVersions = $postsWithVersions > 0
            ? round($totalVersions / $postsWithVersions, 2)
            : 0;

        return view('posts.index', compact(
            'posts',
            'totalPosts',
            'totalVersions',
            'postsWithVersions',
            'averageVersions'
        ));
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
     * Delete one post.
     *
     * Also removes:
     * - Version records
     * - Restore history
     */
    public function destroy(Post $post)
    {
        DB::transaction(function () use ($post) {

            /*
             * Delete restore history.
             */
            VersionRestoreHistory::where(
                'post_id',
                $post->id
            )->delete();

            /*
             * Delete version records.
             */
            $post->versions()->delete();

            /*
             * Delete post.
             */
            $post->delete();
        });

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Duplicate a post.
     */
    public function duplicate(Post $post)
    {
        $duplicate = Post::create([
            'title' => $post->title . ' (Copy)',
            'content' => $post->content,
        ]);

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Post duplicated successfully. New Post #' . $duplicate->id
            );
    }

    /**
     * Bulk delete posts.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'post_ids' => 'required|array|min:1',
            'post_ids.*' => 'integer|exists:posts,id',
        ]);

        $postIds = $validated['post_ids'];

        DB::transaction(function () use ($postIds) {

            /*
             * Delete restore histories.
             */
            VersionRestoreHistory::whereIn(
                'post_id',
                $postIds
            )->delete();

            /*
             * Delete versions.
             */
            DB::table('versions')
                ->where('versionable_type', Post::class)
                ->whereIn('versionable_id', $postIds)
                ->delete();

            /*
             * Delete posts.
             */
            Post::whereIn('id', $postIds)->delete();
        });

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                count($postIds) . ' post(s) deleted successfully.'
            );
    }

    /**
     * Export all posts as CSV.
     */
    public function exportPosts(Request $request): StreamedResponse
    {
        $query = Post::query()
            ->withCount('versions');

        /*
        |--------------------------------------------------------------------------
        | Keep export compatible with search/filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('min_versions')) {
            $query->having(
                'versions_count',
                '>=',
                max(0, (int) $request->min_versions)
            );
        }

        if ($request->filled('max_versions')) {
            $query->having(
                'versions_count',
                '<=',
                max(0, (int) $request->max_versions)
            );
        }

        $posts = $query
            ->orderByDesc('id')
            ->get();

        return response()->streamDownload(function () use ($posts) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Post ID',
                'Title',
                'Content',
                'Version Count',
                'Created At',
                'Updated At',
            ]);

            foreach ($posts as $post) {
                fputcsv($handle, [
                    $post->id,
                    $post->title,
                    $post->content,
                    $post->versions_count,
                    optional($post->created_at)->format('Y-m-d H:i:s'),
                    optional($post->updated_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 'posts-' . now()->format('Y-m-d-His') . '.csv');
    }

    /**
     * Display version history.
     *
     * Existing:
     * - Search
     * - From date
     * - To date
     *
     * Added:
     * - Pagination
     */
    public function showVersions(Request $request, Post $post)
    {
        $query = $post->versions()
            ->orderBy('created_at', 'desc');

        /*
        |--------------------------------------------------------------------------
        | Date filters
        |--------------------------------------------------------------------------
        */
        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        $versions = $query->get();

        if ($request->filled('search')) {

            $search = strtolower(
                trim($request->search)
            );

            $versions = $versions
                ->filter(function ($version) use ($search) {

                    $contents = is_array($version->contents)
                        ? $version->contents
                        : json_decode(
                            $version->contents,
                            true
                        );

                    $title = strtolower(
                        $contents['title'] ?? ''
                    );

                    $content = strtolower(
                        $contents['content'] ?? ''
                    );

                    return str_contains(
                        $title,
                        $search
                    ) || str_contains(
                        $content,
                        $search
                    );
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $totalVersions = $post->versions()->count();

        $latestVersion = $post->versions()
            ->latest('created_at')
            ->first();

        $firstVersion = $post->versions()
            ->oldest('created_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Export link
        |--------------------------------------------------------------------------
        */
        return view('posts.versions', compact(
            'post',
            'versions',
            'totalVersions',
            'latestVersion',
            'firstVersion'
        ));
    }

    /**
     * Export a post's version history as CSV.
     */
    public function exportVersions(Post $post): StreamedResponse
    {
        $versions = $post->versions()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->streamDownload(function () use ($versions, $post) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Post ID',
                'Post Title',
                'Version ID',
                'Created At',
                'Title',
                'Content',
            ]);

            foreach ($versions as $version) {

                $contents = is_array($version->contents)
                    ? $version->contents
                    : json_decode(
                        $version->contents,
                        true
                    );

                fputcsv($handle, [
                    $post->id,
                    $post->title,
                    $version->id,
                    optional($version->created_at)
                        ->format('Y-m-d H:i:s'),
                    $contents['title'] ?? '',
                    $contents['content'] ?? '',
                ]);
            }

            fclose($handle);

        }, 'post-' . $post->id . '-versions-' . now()->format('Y-m-d-His') . '.csv');
    }

    /**
     * Download one version as JSON.
     */
    public function exportVersionJson(
        Post $post,
        $versionId
    ) {
        $version = $post->versions()
            ->where('id', $versionId)
            ->firstOrFail();

        $contents = is_array($version->contents)
            ? $version->contents
            : json_decode(
                $version->contents,
                true
            );

        $data = [
            'post_id' => $post->id,
            'post_title' => $post->title,
            'version_id' => $version->id,
            'created_at' => optional(
                $version->created_at
            )->toDateTimeString(),
            'contents' => $contents,
        ];

        return response()->json(
            $data,
            200,
            [
                'Content-Disposition' =>
                    'attachment; filename="post-' .
                    $post->id .
                    '-version-' .
                    $version->id .
                    '.json"',
            ]
        );
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
        |--------------------------------------------------------------------------
        | Restore selected version
        |--------------------------------------------------------------------------
        */
        $post->revertToVersion($versionId);

        /*
        |--------------------------------------------------------------------------
        | Store restore activity
        |--------------------------------------------------------------------------
        */
        VersionRestoreHistory::create([
            'post_id' => $post->id,
            'version_id' => $version->id,
            'post_title' => $post->title,
            'restored_at' => now(),
        ]);

        return redirect()
            ->route('posts.versions', $post)
            ->with(
                'success',
                'Post successfully restored to Version #' .
                $version->id
            );
    }

    /**
     * Version dashboard.
     */
    public function dashboard()
    {
        $totalPosts = Post::count();

        $totalVersions = DB::table('versions')
            ->where(
                'versionable_type',
                Post::class
            )
            ->count();

        $totalRestores = VersionRestoreHistory::count();

        $postsWithVersions = Post::has('versions')->count();

        $averageVersions = $postsWithVersions > 0
            ? round(
                $totalVersions / $postsWithVersions,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Latest versions
        |--------------------------------------------------------------------------
        */
        $latestVersions = Post::with('versions')
            ->has('versions')
            ->latest('updated_at')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Latest restore activities
        |--------------------------------------------------------------------------
        */
        $restoreHistory = VersionRestoreHistory::with('post')
            ->latest('restored_at')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Most versioned posts
        |--------------------------------------------------------------------------
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