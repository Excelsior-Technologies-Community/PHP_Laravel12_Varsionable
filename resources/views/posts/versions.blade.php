<!DOCTYPE html>
<html>
<head>
    <title>Versions</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 950px;
            margin: auto;
        }

        .version-card {
            background: #ffffff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .btn {
            padding: 7px 15px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 15px;
        }

        .btn:hover {
            background: #c0392b;
        }

        .diff-box {
            background: #f9fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #3498db;
        }

        .field {
            margin-bottom: 12px;
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

        h2 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Versions of "{{ $post->title }}"</h2>

    @foreach($versions as $index => $version)

        @php
            // Current version data
            $currentData = is_array($version->contents)
                ? $version->contents
                : json_decode($version->contents, true);

            // Previous version data
            $previousData = null;

            if($index > 0){
                $prevContents = $versions[$index - 1]->contents;

                $previousData = is_array($prevContents)
                    ? $prevContents
                    : json_decode($prevContents, true);
            }
        @endphp

        <div class="version-card">

            <p><strong>Version ID:</strong> {{ $version->id }}</p>
            <p><strong>Created:</strong> {{ $version->created_at }}</p>

            @if($previousData)
                <div class="diff-box">
                    <h4>Changed Fields:</h4>

                    @foreach($currentData as $field => $value)

                        @php
                            $oldValue = $previousData[$field] ?? null;
                        @endphp

                        @if($oldValue != $value)

                            <div class="field">
                                <div class="field-title">{{ ucfirst($field) }}</div>

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

                </div>
            @endif

            <a href="{{ route('posts.revert', [$post->id, $version->id]) }}" class="btn">
                Revert
            </a>

        </div>

    @endforeach

</div>

</body>
</html>