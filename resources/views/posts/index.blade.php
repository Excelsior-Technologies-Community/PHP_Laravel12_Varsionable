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
            max-width: 1000px;
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

        .btn-warning {
            background: #f39c12;
            color: #ffffff;
        }

        .btn-warning:hover {
            background: #d68910;
        }

        .btn-info {
            background: #2ecc71;
            color: #ffffff;
        }

        .btn-info:hover {
            background: #27ae60;
        }

        .btn-dashboard {
            background: #8e44ad;
            color: #ffffff;
        }

        .btn-dashboard:hover {
            background: #71368a;
        }

        .btn-clear {
            background: #7f8c8d;
            color: #ffffff;
        }

        .btn-clear:hover {
            background: #626f70;
        }

        /* Success Message */
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        /* Search */
        .search-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .search-title {
            margin: 0 0 12px;
            font-size: 18px;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 10px 12px;
            width: 300px;
            max-width: 100%;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.15);
        }

        /* Post Card */
        .card {
            background: white;
            padding: 22px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #2c3e50;
        }

        .card p {
            line-height: 1.6;
        }

        .post-meta {
            color: #777;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .post-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Empty */
        .empty {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .empty h3 {
            margin-bottom: 10px;
        }

        /* Mobile */
        @media (max-width: 700px) {

            body {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .header h1 {
                text-align: center;
            }

            .header-actions {
                justify-content: center;
            }

            .search-form {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                width: 100%;
            }

            .search-form .btn {
                width: 100%;
            }

            .post-actions {
                flex-direction: column;
            }

            .post-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Header -->
    <div class="header">

        <h1>All Posts</h1>

        <div class="header-actions">

            <a href="{{ route('posts.dashboard') }}"
               class="btn btn-dashboard">
                📊 Version Dashboard
            </a>

            <a href="{{ route('posts.create') }}"
               class="btn btn-primary">
                + Create Post
            </a>

        </div>

    </div>


    <!-- Success Message -->
    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


    <!-- Search -->
    <div class="search-box">

        <h3 class="search-title">
            🔎 Search Posts
        </h3>

        <form method="GET"
              action="{{ route('posts.index') }}"
              class="search-form">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by title or content..."
                class="search-input"
            >

            <button type="submit"
                    class="btn btn-primary">
                🔎 Search
            </button>

            <a href="{{ route('posts.index') }}"
               class="btn btn-clear">
                Clear
            </a>

        </form>

    </div>


    <!-- Posts -->
    @forelse($posts as $post)

        <div class="card">

            <h2>
                {{ $post->title }}
            </h2>

            <p>
                {{ $post->content }}
            </p>

            <p class="post-meta">
                <strong>Created:</strong>
                {{ $post->created_at->format('d M Y H:i') }}
            </p>

            <div class="post-actions">

                <a href="{{ route('posts.edit', $post) }}"
                   class="btn btn-warning">
                    ✏️ Edit
                </a>

                <a href="{{ route('posts.versions', $post) }}"
                   class="btn btn-info">
                    🕒 Versions
                </a>

            </div>

        </div>

    @empty

        <div class="empty">

            <h3>
                No posts found.
            </h3>

            @if(request('search'))

                <p>
                    No posts matched
                    "<strong>{{ request('search') }}</strong>".
                </p>

                <a href="{{ route('posts.index') }}"
                   class="btn btn-clear">
                    Clear Search
                </a>

            @else

                <p>
                    You have not created any posts yet.
                </p>

                <a href="{{ route('posts.create') }}"
                   class="btn btn-primary">
                    + Create Your First Post
                </a>

            @endif

        </div>

    @endforelse

</div>

</body>
</html>

