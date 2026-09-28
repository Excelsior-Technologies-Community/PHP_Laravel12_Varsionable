<!DOCTYPE html>
<html>

<head>
    <title>Posts - Versionable</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #2c3e50;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header-actions,
        .post-actions,
        .filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 9px 15px;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            font-family: Arial, sans-serif;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background: #d68910;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-info {
            background: #16a085;
            color: white;
        }

        .btn-info:hover {
            background: #138d75;
        }

        .btn-dashboard {
            background: #8e44ad;
            color: white;
        }

        .btn-dashboard:hover {
            background: #71368a;
        }

        .btn-secondary {
            background: #7f8c8d;
            color: white;
        }

        .btn-secondary:hover {
            background: #626e70;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .stat-card h3 {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .stat-description {
            color: #888;
            font-size: 12px;
            margin-top: 5px;
        }

        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .filter-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .input,
        .select {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            min-height: 40px;
            background: white;
        }

        .search-input {
            width: 280px;
        }

        .post-card {
            background: white;
            padding: 22px;
            margin-bottom: 18px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .post-top {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .post-title {
            margin-top: 0;
            margin-bottom: 10px;
            word-break: break-word;
        }

        .post-content {
            line-height: 1.6;
            color: #555;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .post-meta {
            color: #777;
            font-size: 13px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .version-badge {
            background: #3498db;
            color: white;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 12px;
            white-space: nowrap;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            vertical-align: middle;
        }

        .select-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .bulk-box {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .bulk-info {
            margin-left: auto;
            color: #777;
            font-size: 13px;
        }

        .pagination-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric pagination
        |--------------------------------------------------------------------------
        */

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            min-width: 38px;
            height: 38px;
            padding: 8px 12px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #2c3e50;
            background: white;
            font-size: 14px;
        }

        .pagination a:hover {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination .active {
            background: #3498db;
            color: white;
            border-color: #3498db;
            font-weight: bold;
        }

        .pagination .disabled {
            color: #aaa;
            cursor: not-allowed;
        }

        .page-info {
            margin-top: 12px;
            color: #777;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            background: white;
            padding: 50px 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        .empty h3 {
            margin-bottom: 8px;
        }

        .empty p {
            color: #777;
            margin-bottom: 20px;
        }

        .result-info {
            margin-bottom: 15px;
            color: #666;
            font-size: 14px;
        }

        .sort-badge {
            display: inline-block;
            background: #ecf0f1;
            color: #34495e;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 12px;
            margin-left: 5px;
        }

        @media(max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 800px) {
            body {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions .btn {
                flex: 1;
            }

            .post-top {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }

            .bulk-info {
                margin-left: 0;
                width: 100%;
            }
        }

        @media(max-width: 500px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .header-actions,
            .header-actions .btn {
                width: 100%;
            }

            .post-actions,
            .post-actions .btn {
                width: 100%;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form .input,
            .filter-form .select,
            .filter-form .btn {
                width: 100%;
            }

            .bulk-box {
                flex-direction: column;
                align-items: stretch;
            }

            .bulk-box .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        {{-- =========================================================
         HEADER
    ========================================================== --}}

        <div class="header">

            <h1>📚 All Posts</h1>

            <div class="header-actions">

                <a href="{{ route('posts.dashboard') }}"
                    class="btn btn-dashboard">
                    📊 Dashboard
                </a>

                <a href="{{ route('posts.export', request()->query()) }}"
                    class="btn btn-success">
                    📥 Export CSV
                </a>

                <a href="{{ route('posts.create') }}"
                    class="btn btn-primary">
                    + Create Post
                </a>

            </div>

        </div>


        {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

        @if(session('success'))

        <div class="success">
            ✅ {{ session('success') }}
        </div>

        @endif


        {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

        @if(session('error'))

        <div class="error">
            ❌ {{ session('error') }}
        </div>

        @endif


        {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

        @if($errors->any())

        <div class="error">

            <strong>Please fix the following:</strong>

            <ul style="margin-bottom: 0;">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif


        {{-- =========================================================
         STATISTICS
    ========================================================== --}}

        <div class="stats">

            <div class="stat-card">

                <h3>Total Posts</h3>

                <div class="stat-number">
                    {{ $totalPosts }}
                </div>

                <div class="stat-description">
                    All posts
                </div>

            </div>


            <div class="stat-card">

                <h3>Total Versions</h3>

                <div class="stat-number">
                    {{ $totalVersions }}
                </div>

                <div class="stat-description">
                    Version history records
                </div>

            </div>


            <div class="stat-card">

                <h3>Posts With Versions</h3>

                <div class="stat-number">
                    {{ $postsWithVersions }}
                </div>

                <div class="stat-description">
                    Posts with version history
                </div>

            </div>


            <div class="stat-card">

                <h3>Average Versions / Post</h3>

                <div class="stat-number">
                    {{ number_format((float) $averageVersions, 2) }}
                </div>

                <div class="stat-description">
                    Average version count
                </div>

            </div>

        </div>


        {{-- =========================================================
         FILTERS / SEARCH / SORT
    ========================================================== --}}

        <div class="filter-box">

            <h3>🔎 Search, Filter & Sort</h3>

            <form method="GET"
                action="{{ route('posts.index') }}"
                class="filter-form">

                {{-- Search --}}

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search title or content..."
                    class="input search-input">


                {{-- Minimum versions --}}

                <input
                    type="number"
                    name="min_versions"
                    value="{{ request('min_versions') }}"
                    placeholder="Min versions"
                    min="0"
                    class="input">


                {{-- Maximum versions --}}

                <input
                    type="number"
                    name="max_versions"
                    value="{{ request('max_versions') }}"
                    placeholder="Max versions"
                    min="0"
                    class="input">


                {{-- Sort field --}}

                <select name="sort" class="select">

                    <option value="created_at"
                        {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>
                        Created Date
                    </option>

                    <option value="id"
                        {{ request('sort') == 'id' ? 'selected' : '' }}>
                        ID
                    </option>

                    <option value="title"
                        {{ request('sort') == 'title' ? 'selected' : '' }}>
                        Title
                    </option>

                    <option value="updated_at"
                        {{ request('sort') == 'updated_at' ? 'selected' : '' }}>
                        Updated Date
                    </option>

                    <option value="versions_count"
                        {{ request('sort') == 'versions_count' ? 'selected' : '' }}>
                        Version Count
                    </option>

                </select>


                {{-- Direction --}}

                <select name="direction" class="select">

                    <option value="desc"
                        {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>
                        Descending
                    </option>

                    <option value="asc"
                        {{ request('direction') == 'asc' ? 'selected' : '' }}>
                        Ascending
                    </option>

                </select>


                {{-- Apply --}}

                <button type="submit"
                    class="btn btn-primary">
                    🔍 Apply
                </button>


                {{-- Clear --}}

                <a href="{{ route('posts.index') }}"
                    class="btn btn-secondary">
                    ✖ Clear
                </a>

            </form>

        </div>


        {{-- =========================================================
         CURRENT FILTER INFORMATION
    ========================================================== --}}

        <div class="result-info">

            Showing
            <strong>{{ $posts->firstItem() ?? 0 }}</strong>
            -
            <strong>{{ $posts->lastItem() ?? 0 }}</strong>

            of

            <strong>{{ $posts->total() }}</strong>
            posts

            @if(request('search'))

            <span class="sort-badge">
                Search: {{ request('search') }}
            </span>

            @endif

            @if(request('min_versions') !== null && request('min_versions') !== '')

            <span class="sort-badge">
                Min Versions: {{ request('min_versions') }}
            </span>

            @endif

            @if(request('max_versions') !== null && request('max_versions') !== '')

            <span class="sort-badge">
                Max Versions: {{ request('max_versions') }}
            </span>

            @endif

            <span class="sort-badge">
                Sort:
                {{ ucfirst(str_replace('_', ' ', request('sort', 'created_at'))) }}
                /
                {{ strtoupper(request('direction', 'desc')) }}
            </span>

        </div>


        {{-- =========================================================
         BULK DELETE FORM
         
         IMPORTANT:
         We do NOT wrap the post cards in this form because
         each post has its own Duplicate/Delete forms.
    ========================================================== --}}

        <form method="POST"
            action="{{ route('posts.bulkDestroy') }}"
            id="bulkForm">

            @csrf

            @method('DELETE')

            {{-- We intentionally keep this form empty here.
             The selected checkbox inputs below use form="bulkForm". --}}

        </form>


        <div class="bulk-box">

            <label class="select-label">

                <input
                    type="checkbox"
                    id="selectAll"
                    class="checkbox">

                Select All

            </label>


            <button
                type="submit"
                form="bulkForm"
                class="btn btn-danger"
                onclick="return confirmBulkDelete()">
                🗑 Delete Selected
            </button>


            <span class="bulk-info">

                <span id="selectedCount">0</span>
                post(s) selected

            </span>

        </div>


        {{-- =========================================================
         POSTS
    ========================================================== --}}

        @forelse($posts as $post)

        <div class="post-card">

            <div class="post-top">

                <div style="flex: 1;">

                    <h2 class="post-title">
                        {{ $post->title }}
                    </h2>

                    <div class="post-content">
                        {{ $post->content }}
                    </div>

                </div>


                {{-- Version count --}}

                <div>

                    <span class="version-badge">

                        🕒
                        {{ $post->versions_count }}
                        {{ $post->versions_count == 1 ? 'Version' : 'Versions' }}

                    </span>

                </div>

            </div>


            {{-- =================================================
                 POST META
            ================================================== --}}

            <p class="post-meta">

                <strong>ID:</strong>
                {{ $post->id }}

                &nbsp; | &nbsp;

                <strong>Created:</strong>
                {{ $post->created_at->format('d M Y H:i') }}

                &nbsp; | &nbsp;

                <strong>Updated:</strong>
                {{ $post->updated_at->format('d M Y H:i') }}

            </p>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="post-actions">

                {{-- Bulk checkbox

                     IMPORTANT:
                     form="bulkForm" connects this checkbox to
                     the bulk-delete form without nesting forms.
                --}}

                <label class="select-label">

                    <input
                        type="checkbox"
                        name="post_ids[]"
                        value="{{ $post->id }}"
                        class="post-checkbox checkbox"
                        form="bulkForm">

                    Select

                </label>


                {{-- Edit --}}

                <a href="{{ route('posts.edit', $post) }}"
                    class="btn btn-warning">

                    ✏️ Edit

                </a>


                {{-- Versions --}}

                <a href="{{ route('posts.versions', $post) }}"
                    class="btn btn-info">

                    🕒 Versions

                </a>


                {{-- =================================================
                     DUPLICATE FORM
                ================================================== --}}

                <form
                    method="POST"
                    action="{{ route('posts.duplicate', $post) }}"
                    style="display:inline;">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                        onclick="return confirm('Duplicate this post?')">

                        📄 Duplicate

                    </button>

                </form>


                {{-- =================================================
                     DELETE FORM
                ================================================== --}}

                <form
                    method="POST"
                    action="{{ route('posts.destroy', $post) }}"
                    style="display:inline;">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Delete this post and its complete version history?')">

                        🗑 Delete

                    </button>

                </form>

            </div>

        </div>

        @empty

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}

        <div class="empty">

            <h3>📭 No posts found.</h3>

            <p>
                Try changing your search, sorting or version filters.
            </p>

            <a href="{{ route('posts.create') }}"
                class="btn btn-primary">

                + Create Post

            </a>

        </div>

        @endforelse


        {{-- =========================================================
         PAGINATION
    ========================================================== --}}

        @if($posts->lastPage() > 1)

        <div class="pagination-box">

            <div class="pagination">

                {{-- Previous --}}

                @if($posts->onFirstPage())

                <span class="disabled">
                    ‹
                </span>

                @else

                <a href="{{ $posts->appends(request()->query())->previousPageUrl() }}">
                    ‹
                </a>

                @endif


                {{-- Numeric pages --}}

                @for($page = 1; $page <= $posts->lastPage(); $page++)

                    @if($page == $posts->currentPage())

                    <span class="active">
                        {{ $page }}
                    </span>

                    @else

                    <a href="{{ $posts->appends(request()->query())->url($page) }}">
                        {{ $page }}
                    </a>

                    @endif

                    @endfor


                    {{-- Next --}}

                    @if($posts->hasMorePages())

                    <a href="{{ $posts->appends(request()->query())->nextPageUrl() }}">
                        ›
                    </a>

                    @else

                    <span class="disabled">
                        ›
                    </span>

                    @endif

            </div>


            <div class="page-info">

                Page
                <strong>{{ $posts->currentPage() }}</strong>
                of
                <strong>{{ $posts->lastPage() }}</strong>

                —
                8 posts per page

            </div>

        </div>

        @endif

    </div>


    <script>
        /*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/

        const selectAll = document.getElementById('selectAll');

        const postCheckboxes = document.querySelectorAll('.post-checkbox');

        const selectedCount = document.getElementById('selectedCount');


        /*
        |--------------------------------------------------------------------------
        | Update selected count
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {
            const selected = document.querySelectorAll(
                '.post-checkbox:checked'
            ).length;

            selectedCount.textContent = selected;
        }


        /*
        |--------------------------------------------------------------------------
        | Select all checkbox
        |--------------------------------------------------------------------------
        */

        selectAll.addEventListener('change', function() {

            postCheckboxes.forEach(function(checkbox) {

                checkbox.checked = selectAll.checked;

            });

            updateSelectedCount();

        });


        /*
        |--------------------------------------------------------------------------
        | Individual checkbox changes
        |--------------------------------------------------------------------------
        */

        postCheckboxes.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                const total = postCheckboxes.length;

                const checked = document.querySelectorAll(
                    '.post-checkbox:checked'
                ).length;

                /*
                | All selected
                */

                if (checked === total && total > 0) {

                    selectAll.checked = true;

                } else {

                    selectAll.checked = false;

                }

                updateSelectedCount();

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Bulk delete confirmation
        |--------------------------------------------------------------------------
        */

        function confirmBulkDelete() {
            const selected = document.querySelectorAll(
                '.post-checkbox:checked'
            );

            if (selected.length === 0) {

                alert('Please select at least one post.');

                return false;

            }


            return confirm(
                'Are you sure you want to delete ' +
                selected.length +
                ' selected post(s) and their version history?'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Initial count
        |--------------------------------------------------------------------------
        */

        updateSelectedCount();
    </script>

</body>

</html>