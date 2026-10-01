<!DOCTYPE html>
<html>
<head>

    <title>Version History - {{ $post->title }}</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
            margin: 0;
            color: #2c3e50;
        }

        .container {
            max-width: 1100px;
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
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
        }

        .header h1 {
            margin: 0;
        }

        .header-actions,
        .version-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        .btn-success {
            background: #2ecc71;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-secondary {
            background: #7f8c8d;
            color: white;
        }

        .btn-info {
            background: #16a085;
            color: white;
        }

        .btn-dark {
            background: #34495e;
            color: white;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
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
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
        }

        .stat-card h3 {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        .number {
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
        }

        .filter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-input {
            width: 300px;
        }

        .timeline {
            position: relative;
            padding-left: 35px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #3498db;
        }

        .version-card {
            background: white;
            padding: 22px;
            margin-bottom: 25px;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }

        .version-card::before {
            content: '';
            position: absolute;
            left: -29px;
            top: 25px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #3498db;
            border: 3px solid white;
        }

        .version-header {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .version-number {
            font-size: 18px;
            font-weight: bold;
        }

        .date {
            color: #777;
            font-size: 14px;
        }

        .contents {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .field {
            margin-bottom: 15px;
        }

        .field:last-child {
            margin-bottom: 0;
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

        .diff-box {
            background: #f9fafc;
            padding: 15px;
            border-left: 4px solid #3498db;
            border-radius: 6px;
            margin-top: 15px;
        }

        .badge {
            background: #3498db;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            display: inline-block;
        }

        .badge-tag {
            background: #8e44ad;
            color: white;
        }

        .badge-lock {
            background: #e67e22;
            color: white;
        }

        .empty {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
        }

        .selective-box {
            background: #eef7fc;
            border: 1px solid #bce8f1;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <div>

            <h1>
                🕒 Version History
            </h1>

            <p style="margin: 4px 0 0 0;">
                Post:
                <strong>{{ $post->title }}</strong>
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('posts.versions.compare', $post) }}"
                class="btn btn-warning"
            >
                ⚡ Compare Diff
            </a>

            <a
                href="{{ route('posts.index') }}"
                class="btn btn-secondary"
            >
                ← Posts
            </a>

            <a
                href="{{ route('posts.dashboard') }}"
                class="btn btn-primary"
            >
                📊 Dashboard
            </a>

            <a
                href="{{ route('posts.versions.export', $post) }}"
                class="btn btn-success"
            >
                📥 Export CSV
            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card">
            <h3>Total Versions</h3>
            <div class="number">{{ $totalVersions }}</div>
        </div>

        <div class="stat-card">
            <h3>Latest Version</h3>
            <div class="number">#{{ $latestVersion->id ?? '—' }}</div>
        </div>

        <div class="stat-card">
            <h3>First Version</h3>
            <div class="number">#{{ $firstVersion->id ?? '—' }}</div>
        </div>

        <div class="stat-card">
            <h3>Milestone Tags</h3>
            <div class="number">{{ $versions->whereNotNull('label')->count() }}</div>
        </div>

    </div>

    <!-- Filter -->

    <div class="filter-box">

        <form method="GET" class="filter-form">

            <input
                type="text"
                name="search"
                class="filter-input search-input"
                placeholder="Search version title or content..."
                value="{{ request('search') }}"
            >

            <input
                type="date"
                name="from_date"
                class="filter-input"
                value="{{ request('from_date') }}"
            >

            <input
                type="date"
                name="to_date"
                class="filter-input"
                value="{{ request('to_date') }}"
            >

            <button type="submit" class="btn btn-primary">
                Filter
            </button>

            <a
                href="{{ route('posts.versions', $post) }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </form>

    </div>

    <!-- Version List -->

    @if(count($versions) > 0)

        <div class="timeline">

            @foreach($versions as $index => $version)

                @php
                    $currentData = is_array($version->contents)
                        ? $version->contents
                        : json_decode($version->contents, true);

                    $previousVersion = $versions[$index + 1] ?? null;

                    $previousData = null;

                    if ($previousVersion) {
                        $previousData = is_array($previousVersion->contents)
                            ? $previousVersion->contents
                            : json_decode($previousVersion->contents, true);
                    }
                @endphp

                <div class="version-card">

                    <div class="version-header">

                        <div>
                            <div class="version-number">
                                Version #{{ $version->id }}

                                @if($index === 0)
                                    <span class="badge">Latest</span>
                                @endif

                                @if($version->label)
                                    <span class="badge badge-tag">🏷️ {{ $version->label }}</span>
                                @endif

                                @if($version->is_locked)
                                    <span class="badge badge-lock">🔒 Locked</span>
                                @endif
                            </div>

                            <div class="date">
                                {{ $version->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <!-- Milestone Tagging & Lock Controls -->
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                            <form method="POST" action="{{ route('posts.version.tag', [$post->id, $version->id]) }}" style="display: flex; gap: 4px;">
                                @csrf
                                <input type="text" name="label" placeholder="Custom tag (e.g. v1.0)" value="{{ $version->label }}" style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; width: 140px;">
                                <button type="submit" class="btn btn-dark" style="font-size: 11px; padding: 4px 8px; min-height: 28px;">Tag</button>
                            </form>

                            <form method="POST" action="{{ route('posts.version.lock', [$post->id, $version->id]) }}">
                                @csrf
                                <button type="submit" class="btn {{ $version->is_locked ? 'btn-warning' : 'btn-secondary' }}" style="font-size: 11px; padding: 4px 8px; min-height: 28px;">
                                    {{ $version->is_locked ? '🔓 Unlock' : '🔒 Lock' }}
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="contents">

                        <div class="field">
                            <div class="field-title">Title</div>
                            {{ $currentData['title'] ?? '—' }}
                        </div>

                        <div class="field">
                            <div class="field-title">Content</div>
                            {{ $currentData['content'] ?? '—' }}
                        </div>

                    </div>

                    @if($previousData)

                        <div class="diff-box">

                            <h4 style="margin-top: 0;">Changed Fields</h4>

                            @php
                                $hasChanges = false;
                            @endphp

                            @foreach($currentData as $field => $value)

                                @php
                                    $oldValue = $previousData[$field] ?? null;
                                @endphp

                                @if($oldValue != $value)

                                    @php
                                        $hasChanges = true;
                                    @endphp

                                    <div class="field">

                                        <div class="field-title">
                                            {{ ucfirst($field) }}
                                        </div>

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

                            @if(!$hasChanges)
                                <p style="margin: 0; color: #777;">No field changes detected.</p>
                            @endif

                        </div>

                    @endif

                    <!-- Selective Field-Level Restore (Partial Rollback) Form -->
                    <div class="selective-box">
                        <form method="POST" action="{{ route('posts.revert-selective', [$post->id, $version->id]) }}" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            @csrf
                            <div style="display: flex; gap: 15px; align-items: center;">
                                <strong style="font-size: 13px; color: #2980b9;">⏪ Selective Partial Restore:</strong>
                                <label style="font-size: 13px; cursor: pointer;">
                                    <input type="checkbox" name="fields[]" value="title" checked> Title
                                </label>
                                <label style="font-size: 13px; cursor: pointer;">
                                    <input type="checkbox" name="fields[]" value="content" checked> Content
                                </label>
                            </div>
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Restore selected field(s) from Version #{{ $version->id }}?')" style="font-size: 12px; padding: 5px 10px;">
                                Partial Restore
                            </button>
                        </form>
                    </div>

                    <div class="version-actions" style="margin-top: 15px;">

                        <!-- Full Revert -->
                        <form method="POST" action="{{ route('posts.revert', [$post->id, $version->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Restore all attributes to Version #{{ $version->id }}?')">
                                ↩ Full Revert
                            </button>
                        </form>

                        <!-- Compare with Current -->
                        <a href="{{ route('posts.versions.compare', [$post->id, 'v1' => $version->id, 'v2' => 'current']) }}" class="btn btn-warning">
                            ⚡ Diff vs Live
                        </a>

                        <!-- JSON Export -->
                        <a href="{{ route('posts.version.json', [$post->id, $version->id]) }}" class="btn btn-info">
                            📄 JSON
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty">
            <h3>No versions found.</h3>
            <p>Try changing your filters.</p>
        </div>

    @endif

</div>

</body>
</html>