<!DOCTYPE html>
<html>
<head>
    <title>Create Post - Versionable</title>

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
            max-width: 800px;
            margin: auto;
        }

        /* Header */
        .header {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        /* Card */
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.15);
        }

        textarea.form-control {
            min-height: 180px;
            resize: vertical;
        }

        /* Errors */
        .error-box {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Buttons */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

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

        .btn-success {
            background: #2ecc71;
            color: #ffffff;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .btn-cancel {
            background: #7f8c8d;
            color: #ffffff;
        }

        .btn-cancel:hover {
            background: #626f70;
        }

        /* Mobile */
        @media (max-width: 700px) {

            body {
                padding: 15px;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>➕ Create Post</h1>
    </div>

    <div class="card">

        @if($errors->any())

            <div class="error-box">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        <form method="POST" action="{{ route('posts.store') }}">

            @csrf

            <div class="form-group">

                <label for="title">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    placeholder="Enter post title"
                    required
                >

            </div>

            <div class="form-group">

                <label for="content">
                    Content
                </label>

                <textarea
                    id="content"
                    name="content"
                    class="form-control"
                    placeholder="Enter post content"
                    required
                >{{ old('content') }}</textarea>

            </div>

            <div class="form-actions">

                <button type="submit"
                        class="btn btn-success">
                    💾 Save Post
                </button>

                <a href="{{ route('posts.index') }}"
                   class="btn btn-cancel">
                    ✖ Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>