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

        /* Header */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin-bottom: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Buttons */

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 9px 16px;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            font-family: Arial, sans-serif;
            white-space: nowrap;
        }

        .btn-primary {
            background: #3498db;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #7f8c8d;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #626f70;
        }

        .btn-success {
            background: #2ecc71;
            color: #ffffff;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        /* Statistics */

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
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Filter */

        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .filter-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-input {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.15);
        }

        .search-input {
            width: 300px;
            max-width: 100%;
        }

        /* Success */

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        /* Timeline */

        .timeline {
            position: relative;
            margin: 30px 0;
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
            margin-bottom: 30px;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 0 0 2px #3498db;
        }

        .version-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Contents */

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

        /* Difference */

        .diff-box {
            background: #f9fafc;
            padding: 15px;
            border-left: 4px solid #3498db;
            border-radius: 6px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .diff-box h4 {
            margin-top: 0;
        }

        /* Badge */

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 15px;
            font-size: 12px;
            background: #3498db;
            color: white;
            margin-left: 5px;
        }

        /* Empty */

        .empty {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        /* Restore Form */

        .restore-form {
            margin-top: 15px;
        }

        /* Mobile */

        @media (max-width: 800px) {

            body {
                padding: 15px;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                justify-content: center;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-input {
                width: 100%;
            }

            .filter-form .btn {
                width: 100%;
            }

            .version-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .restore-form .btn {
                width: 100%;
            }
        }

        @media (max-width: 500px) {

            .stats {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <!-- Header -->

    <div class="header">

        <div>

            <h1>
                Version History
            </h1>

            <p>
                Post:
                <strong>{{ $post->title }}</strong>
            </p>

        </div>

        <div class="header-actions">

            <a href="{{ route('posts.index') }}"
               class="btn btn-secondary">
                ← Posts
            </a>

            <a href="{{ route('posts.dashboard') }}"
               class="btn btn-primary">
                📊 Dashboard
            </a>

        </div>

    </div>


    <!-- Success Message -->

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card">

            <h3>
                Total Versions
            </h3>

            <div class="number">
                {{ $totalVersions }}
            </div>

        </div>


        <div class="stat-card">

            <h3>
                First Version
            </h3>

            <div class="number">

                @if($firstVersion)

                    #{{ $firstVersion->id }}

                @else

                    —

                @endif

            </div>

        </div>


        <div class="stat-card">

            <h3>
                Latest Version
            </h3>

            <div class="number">

                @if($latestVersion)

                    #{{ $latestVersion->id }}

                @else

                    —

                @endif

            </div>

        </div>


        <div class="stat-card">

            <h3>
                Current Post
            </h3>

            <div class="number">
                #{{ $post->id }}
            </div>

        </div>

    </div>


    <!-- Search & Filter -->

    <div class="filter-box">

        <h3>
            🔎 Search & Filter Versions
        </h3>

        <form method="GET"
              action="{{ route('posts.versions', $post) }}"
              class="filter-form">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search version content..."
                class="filter-input search-input"
            >

            <input
                type="date"
                name="from_date"
                value="{{ request('from_date') }}"
                class="filter-input"
            >

            <input
                type="date"
                name="to_date"
                value="{{ request('to_date') }}"
                class="filter-input"
            >

            <button type="submit"
                    class="btn btn-primary">
                🔎 Apply Filter
            </button>

            <a href="{{ route('posts.versions', $post) }}"
               class="btn btn-secondary">
                Clear
            </a>

        </form>

    </div>


    <!-- Version Timeline -->

    <h2>
        🕒 Version Timeline
    </h2>


    @if($versions->count() > 0)

        <div class="timeline">

            @foreach($versions as $index => $version)

                @php

                    $currentData = is_array($version->contents)
                        ? $version->contents
                        : json_decode($version->contents, true);

                    /*
                     * Versions are displayed newest first.
                     * Therefore the next array item is the older version.
                     */

                    $previousData = null;

                    if(isset($versions[$index + 1])) {

                        $prevContents = $versions[$index + 1]->contents;

                        $previousData = is_array($prevContents)
                            ? $prevContents
                            : json_decode($prevContents, true);

                    }

                @endphp


                <div class="version-card">

                    <!-- Version Header -->

                    <div class="version-header">

                        <div class="version-number">

                            Version #{{ $version->id }}

                            @if($index === 0)

                                <span class="badge">
                                    Latest
                                </span>

                            @endif

                        </div>

                        <div class="date">

                            {{ $version->created_at->format('d M Y, h:i A') }}

                        </div>

                    </div>


                    <!-- Version Content -->

                    <div class="contents">

                        <div class="field">

                            <div class="field-title">
                                Title
                            </div>

                            {{ $currentData['title'] ?? '—' }}

                        </div>


                        <div class="field">

                            <div class="field-title">
                                Content
                            </div>

                            {{ $currentData['content'] ?? '—' }}

                        </div>

                    </div>


                    <!-- Difference -->

                    @if($previousData)

                        <div class="diff-box">

                            <h4>
                                Changed Fields
                            </h4>

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

                                            <span class="old">
                                                Old:
                                            </span>

                                            {{ $oldValue ?? '—' }}

                                        </div>

                                        <div>

                                            <span class="new">
                                                New:
                                            </span>

                                            {{ $value ?? '—' }}

                                        </div>

                                    </div>

                                @endif

                            @endforeach


                            @if(!$hasChanges)

                                <p>
                                    No field changes detected.
                                </p>

                            @endif

                        </div>

                    @endif


                    <!-- Restore -->

                    <form
                        method="POST"
                        action="{{ route('posts.revert', [$post->id, $version->id]) }}"
                        class="restore-form"
                        onsubmit="return confirm('Are you sure you want to restore this version?');"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-danger">

                            ↩ Restore Version #{{ $version->id }}

                        </button>

                    </form>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty">

            <h3>
                No versions found.
            </h3>

            <p>
                Try changing your search or date filters.
            </p>

        </div>

    @endif

</div>

</body>
</html>
