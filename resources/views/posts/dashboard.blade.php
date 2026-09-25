<!DOCTYPE html>
<html>
<head>

    <title>Version Dashboard</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-secondary {
            background: #7f8c8d;
            color: white;
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .stat-card h3 {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        .section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .section h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            background: #3498db;
            color: white;
            font-size: 12px;
        }

        .restore-badge {
            background: #e67e22;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        /*
        |--------------------------------------------------------------------------
        | Progress
        |--------------------------------------------------------------------------
        */

        .progress {
            background: #eee;
            height: 10px;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar {
            background: #3498db;
            height: 100%;
        }

        @media(max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .header {
                display: block;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <div>

            <h1>📊 Version History Dashboard</h1>

            <p>
                Laravel Versionable Management Dashboard
            </p>

        </div>

        <div>

            <a href="{{ route('posts.index') }}"
               class="btn btn-secondary">
                ← All Posts
            </a>

        </div>

    </div>


    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card">

            <h3>Total Posts</h3>

            <div class="stat-number">
                {{ $totalPosts }}
            </div>

        </div>


        <div class="stat-card">

            <h3>Total Versions</h3>

            <div class="stat-number">
                {{ $totalVersions }}
            </div>

        </div>


        <div class="stat-card">

            <h3>Total Restores</h3>

            <div class="stat-number">
                {{ $totalRestores }}
            </div>

        </div>


        <div class="stat-card">

            <h3>Average Versions / Post</h3>

            <div class="stat-number">
                {{ $averageVersions }}
            </div>

        </div>

    </div>


    <!-- Posts With Versions -->

    <div class="section">

        <h2>📝 Posts With Version History</h2>

        @if($mostVersionedPosts->count())

            <table>

                <thead>

                    <tr>
                        <th>Post</th>
                        <th>Versions</th>
                        <th>Version Activity</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($mostVersionedPosts as $post)

                        @php

                            $percentage = $totalVersions > 0
                                ? min(
                                    100,
                                    ($post->versions_count / $totalVersions) * 100
                                )
                                : 0;

                        @endphp

                        <tr>

                            <td>
                                <strong>
                                    {{ $post->title }}
                                </strong>
                            </td>

                            <td>

                                <span class="badge">
                                    {{ $post->versions_count }}
                                </span>

                            </td>

                            <td>

                                <div class="progress">

                                    <div
                                        class="progress-bar"
                                        style="width: {{ $percentage }}%"
                                    ></div>

                                </div>

                            </td>

                            <td>

                                <a
                                    href="{{ route('posts.versions', $post) }}"
                                    class="btn btn-primary"
                                >
                                    View Versions
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                No versioned posts available.
            </div>

        @endif

    </div>


    <!-- Latest Version Activity -->

    <div class="section">

        <h2>🕒 Latest Version Activity</h2>

        @if($latestVersions->count())

            <table>

                <thead>

                    <tr>
                        <th>Post</th>
                        <th>Last Updated</th>
                        <th>Versions</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($latestVersions as $post)

                        <tr>

                            <td>
                                {{ $post->title }}
                            </td>

                            <td>
                                {{ $post->updated_at->format('d M Y, h:i A') }}
                            </td>

                            <td>

                                <span class="badge">
                                    {{ $post->versions->count() }}
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('posts.versions', $post) }}"
                                    class="btn btn-primary"
                                >
                                    Timeline
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">
                No version activity available.
            </div>

        @endif

    </div>


    <!-- Restore History -->

    <div class="section">

        <h2>↩ Recent Restore Activity</h2>

        @if($restoreHistory->count())

            <table>

                <thead>

                    <tr>
                        <th>Post</th>
                        <th>Version</th>
                        <th>Restored At</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($restoreHistory as $restore)

                        <tr>

                            <td>

                                <strong>
                                    {{ $restore->post_title }}
                                </strong>

                            </td>

                            <td>

                                <span class="badge restore-badge">
                                    Version #{{ $restore->version_id }}
                                </span>

                            </td>

                            <td>

                                {{ $restore->restored_at->format('d M Y, h:i A') }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty">

                <p>
                    No restore activity yet.
                </p>

                <p>
                    Restore a previous version to see activity here.
                </p>

            </div>

        @endif

    </div>

</div>

</body>
</html>