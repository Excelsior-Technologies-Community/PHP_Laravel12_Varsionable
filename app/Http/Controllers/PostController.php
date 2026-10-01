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

    /**
     * Side-by-Side Visual Diff Inspector & Inline Change Highlighter.
     */
    public function compareVersions(Request $request, Post $post)
    {
        $versions = $post->versions()->orderBy('created_at', 'desc')->get();

        $v1Id = $request->get('v1');
        $v2Id = $request->get('v2');

        if (!$v1Id && $versions->count() > 1) {
            $v1Id = $versions[1]->id;
        } elseif (!$v1Id && $versions->count() > 0) {
            $v1Id = $versions[0]->id;
        }

        if (!$v2Id && $versions->count() > 0) {
            $v2Id = $versions[0]->id;
        }

        $v1Data = ['title' => $post->title, 'content' => $post->content, 'created_at' => $post->updated_at, 'label' => 'Current Live Post'];
        $v2Data = ['title' => $post->title, 'content' => $post->content, 'created_at' => $post->updated_at, 'label' => 'Current Live Post'];

        $v1Model = null;
        $v2Model = null;

        if ($v1Id && $v1Id !== 'current') {
            $v1Model = $post->versions()->find($v1Id);
            if ($v1Model) {
                $contents = is_array($v1Model->contents) ? $v1Model->contents : json_decode($v1Model->contents, true);
                $v1Data = [
                    'title' => $contents['title'] ?? '',
                    'content' => $contents['content'] ?? '',
                    'created_at' => $v1Model->created_at,
                    'label' => $v1Model->label ?: "Version #{$v1Model->id}",
                ];
            }
        }

        if ($v2Id && $v2Id !== 'current') {
            $v2Model = $post->versions()->find($v2Id);
            if ($v2Model) {
                $contents = is_array($v2Model->contents) ? $v2Model->contents : json_decode($v2Model->contents, true);
                $v2Data = [
                    'title' => $contents['title'] ?? '',
                    'content' => $contents['content'] ?? '',
                    'created_at' => $v2Model->created_at,
                    'label' => $v2Model->label ?: "Version #{$v2Model->id}",
                ];
            }
        }

        $titleDiff = \App\Services\TextDiffHelper::renderWordDiff($v1Data['title'], $v2Data['title']);
        $contentDiff = \App\Services\TextDiffHelper::renderWordDiff($v1Data['content'], $v2Data['content']);

        return view('posts.compare', compact(
            'post',
            'versions',
            'v1Id',
            'v2Id',
            'v1Data',
            'v2Data',
            'v1Model',
            'v2Model',
            'titleDiff',
            'contentDiff'
        ));
    }

    /**
     * Custom Version Milestone Tagging.
     */
    public function tagVersion(Request $request, Post $post, $versionId)
    {
        $version = $post->versions()->where('id', $versionId)->firstOrFail();

        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
        ]);

        $version->label = $validated['label'] ?: null;
        $version->save();

        return back()->with('success', "Version #{$version->id} label updated to '" . ($version->label ?: 'None') . "'.");
    }

    /**
     * Version Lock Toggle.
     */
    public function toggleLockVersion(Request $request, Post $post, $versionId)
    {
        $version = $post->versions()->where('id', $versionId)->firstOrFail();

        $version->is_locked = !$version->is_locked;
        $version->save();

        $status = $version->is_locked ? 'Locked 🔒' : 'Unlocked 🔓';

        return back()->with('success', "Version #{$version->id} status changed to {$status}.");
    }

    /**
     * Selective Field-Level Restore (Partial Rollback).
     */
    public function revertSelective(Request $request, Post $post, $versionId)
    {
        $version = $post->versions()->where('id', $versionId)->firstOrFail();

        $validated = $request->validate([
            'fields' => 'required|array|min:1',
            'fields.*' => 'in:title,content',
        ]);

        $contents = is_array($version->contents) ? $version->contents : json_decode($version->contents, true);

        $restoredFields = [];

        if (in_array('title', $validated['fields']) && isset($contents['title'])) {
            $post->title = $contents['title'];
            $restoredFields[] = 'Title';
        }

        if (in_array('content', $validated['fields']) && isset($contents['content'])) {
            $post->content = $contents['content'];
            $restoredFields[] = 'Content';
        }

        $post->save();

        VersionRestoreHistory::create([
            'post_id' => $post->id,
            'version_id' => $version->id,
            'post_title' => $post->title,
            'restored_at' => now(),
        ]);

        $fieldList = implode(' & ', $restoredFields);

        return redirect()
            ->route('posts.versions', $post)
            ->with('success', "Selective Restore Complete! Successfully restored {$fieldList} from Version #{$version->id}.");
    }
}